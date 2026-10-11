<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\Error;
use Factuarea\Sdk\SDKConfiguration;
use Factuarea\Sdk\Utils\JSON;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
use Factuarea\Sdk\Utils\Retry\RetryStrategy;
use Factuarea\Sdk\Utils\Retry\RetryUtils;
use Factuarea\Sdk\Utils\Utils;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Speakeasy\Serializer\DeserializationContext;

/** Uses the SDK's configured HTTP client, security, hooks and native error hierarchy. */
final readonly class Transport
{
    public function __construct(private SDKConfiguration $configuration, private bool $currentSecurity = false)
    {
    }

    /**
     * @template T of WireModel
     * @param  array<string, scalar|null>  $query
     * @param  class-string<T>  $responseType
     * @param  array<string, string>  $headers
     * @param  list<int>|null  $successStatuses  Explicit native Source statuses; the normal profile keeps its existing behavior.
     * @return CrmResponse<T>
     */
    public function request(string $method, string $path, string $operationId, string $responseType,
        array $query = [], ?WireModel $body = null, ?string $idempotencyKey = null, bool $hasEffect = true, array $headers = [], ?string $originalBodySha256 = null, ?array $successStatuses = null): CrmResponse
    {
        $mutating = $method !== 'GET';
        if ($mutating && ($idempotencyKey === null || preg_match('/^[\x20-\x7e]{1,255}$/D', $idempotencyKey) !== 1)) {
            throw new \InvalidArgumentException('Supply the original printable ASCII Idempotency-Key (1–255 characters).');
        }
        $baseUrl = rtrim($this->configuration->getTemplatedServerUrl(), '/');
        $headers = ['Accept' => 'application/json', 'user-agent' => $this->configuration->userAgent] + $headers;
        if ($idempotencyKey !== null) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }
        $payload = null;
        if ($body !== null) {
            $body->validate();
            $headers['Content-Type'] = 'application/json';
            $payload = json_encode($body, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
        }
        $context = new Hooks\HookContext($this->configuration, $baseUrl, $operationId, null, $this->configuration->securitySource);
        $request = new Request($method, $baseUrl.'/'.$path, $headers, $payload);
        $request = $this->configuration->hooks->beforeRequest(new Hooks\BeforeRequestContext($context), $request);
        if ($originalBodySha256 !== null && ($request->getMethod() !== $method
            || (string) $request->getUri() !== $baseUrl.'/'.$path
            || $request->getHeaderLine('Idempotency-Key') !== $idempotencyKey
            || ! hash_equals($originalBodySha256, hash('sha256', (string) $request->getBody())))) {
            throw new \LogicException('The original export method, URI, key and wire bytes cannot be replaced by a hook.');
        }
        $options = Utils::convertHeadersToOptions($request, ['http_errors' => false, 'query' => $query]);
        if ($originalBodySha256 !== null || $this->currentSecurity) {
            $options['allow_redirects'] = false;
        }
        $request = Utils::removeHeaders($request);
        $client = $this->configuration->client ?? throw new \LogicException('Build the canonical Factuarea client before using CRM.');
        try {
            $send = fn () => ($originalBodySha256 !== null || $this->currentSecurity ? $this->currentClient() : $client)->send($request, $options);
            $retry = $this->configuration->retryConfig ?? new RetryConfigBackoff(500, 60000, 1.5, 3600000, true);
            $response = ($mutating || $retry->strategy === RetryStrategy::NONE) ? $send()
                : RetryUtils::retryWrapper($send, $retry, ['429', '5xx']);
        } catch (GuzzleException $exception) {
            try {
                $response = $this->configuration->hooks->afterError(new Hooks\AfterErrorContext($context), null, $exception);
            } catch (\Throwable $failure) {
                if ($mutating && $hasEffect) {
                    throw new UnconfirmedMutationException($operationId, $idempotencyKey, $failure);
                }
                throw $failure;
            }
        }
        if ($response->getStatusCode() >= 400) {
            $response = $this->configuration->hooks->afterError(new Hooks\AfterErrorContext($context), $response, null);
        }
        $status = $response->getStatusCode();
        if (! Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new APIException('Unknown content type received', $status, (string) $response->getBody(), $response);
        }
        if ($status >= 400) {
            $error = JSON::createSerializer()->deserialize((string) $response->getBody(), Error::class, 'json',
                DeserializationContext::create()->setRequireAllRequiredProperties(true));
            if (! $error instanceof Error) {
                throw new APIException('API error occurred', $status, (string) $response->getBody(), $response);
            }
            $error->rawResponse = $response;
            throw $error->toException();
        }
        if ($status < 200 || $status >= 300) {
            throw new APIException('Unknown status code received', $status, (string) $response->getBody(), $response);
        }
        if ($successStatuses !== null && ! in_array($status, $successStatuses, true)) {
            throw new APIException('The status code does not match the native Source operation.', $status, (string) $response->getBody(), $response);
        }
        $response = $this->configuration->hooks->afterSuccess(new Hooks\AfterSuccessContext($context), $response);
        if ($successStatuses !== null && ! in_array($response->getStatusCode(), $successStatuses, true)) {
            throw new APIException('The hook must retain a native Source success status.', $response->getStatusCode(), (string) $response->getBody(), $response);
        }
        $values = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \UnexpectedValueException('The CRM response must be an object.');
        }

        return new CrmResponse($responseType::fromArray((array) $values), $response, $idempotencyKey);
    }

    /** Read the native binary using the configured SDK client/security/hooks/error hierarchy. */
    public function requestStream(string $path, string $operationId, ?string $idempotencyKey = null): ResponseInterface
    {
        $baseUrl = rtrim($this->configuration->getTemplatedServerUrl(), '/');
        $context = new Hooks\HookContext($this->configuration, $baseUrl, $operationId, null, $this->configuration->securitySource);
        $headers = ['Accept' => 'text/csv', 'user-agent' => $this->configuration->userAgent];
        if ($idempotencyKey !== null) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }
        $request = $this->configuration->hooks->beforeRequest(new Hooks\BeforeRequestContext($context),
            new Request('GET', $baseUrl.'/'.$path, $headers));
        if ($request->getMethod() !== 'GET' || (string) $request->getUri() !== $baseUrl.'/'.$path
            || $request->getHeaderLine('Idempotency-Key') !== ($idempotencyKey ?? '') || (string) $request->getBody() !== '') {
            throw new \LogicException('A native export download is the same Query-only GET.');
        }
        $options = Utils::convertHeadersToOptions($request, ['http_errors' => false, 'allow_redirects' => false, 'stream' => true]);
        $request = Utils::removeHeaders($request);
        $client = $this->currentClient();
        try {
            $response = $client->send($request, $options);
        } catch (GuzzleException $exception) {
            $response = $this->configuration->hooks->afterError(new Hooks\AfterErrorContext($context), null, $exception);
        }
        if ($response->getStatusCode() >= 400) {
            $response = $this->configuration->hooks->afterError(new Hooks\AfterErrorContext($context), $response, null);
        }
        if ($response->getStatusCode() >= 400 && Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json')) {
            $error = JSON::createSerializer()->deserialize((string) $response->getBody(), Error::class, 'json',
                DeserializationContext::create()->setRequireAllRequiredProperties(true));
            if ($error instanceof Error) {
                $error->rawResponse = $response;
                throw $error->toException();
            }
        }
        if ($response->getStatusCode() !== 200 || ! Utils::matchContentType($response->getHeaderLine('Content-Type'), 'text/csv')) {
            throw new APIException('The native export download requires a ready CSV stream.', $response->getStatusCode(), '', $response);
        }

        $response = $this->configuration->hooks->afterSuccess(new Hooks\AfterSuccessContext($context), $response);
        if ($response->getStatusCode() !== 200 || ! Utils::matchContentType($response->getHeaderLine('Content-Type'), 'text/csv')) {
            throw new APIException('The native download hook must preserve its ready CSV response.', $response->getStatusCode(), '', $response);
        }

        return $response;
    }

    /** Resolve the configured native security source immediately before factual export I/O. */
    private function currentClient(): \GuzzleHttp\ClientInterface
    {
        $client = $this->configuration->defaultClient ?? $this->configuration->client
            ?? throw new \LogicException('Build the canonical Factuarea client before using CRM.');

        return $this->configuration->hasSecurity()
            ? Utils::configureSecurityClient($client, $this->configuration->getSecurity()) : $client;
    }
}
