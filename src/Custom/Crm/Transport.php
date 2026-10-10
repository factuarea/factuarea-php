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
use Speakeasy\Serializer\DeserializationContext;

/** Uses the SDK's configured HTTP client, security, hooks and native error hierarchy. */
final readonly class Transport
{
    public function __construct(private SDKConfiguration $configuration)
    {
    }

    /**
     * @template T of WireModel
     * @param  array<string, scalar|null>  $query
     * @param  class-string<T>  $responseType
     * @param  array<string, string>  $headers
     * @return CrmResponse<T>
     */
    public function request(string $method, string $path, string $operationId, string $responseType,
        array $query = [], ?WireModel $body = null, ?string $idempotencyKey = null, bool $hasEffect = true, array $headers = []): CrmResponse
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
        $options = Utils::convertHeadersToOptions($request, ['http_errors' => false, 'query' => $query]);
        $request = Utils::removeHeaders($request);
        $client = $this->configuration->client ?? throw new \LogicException('Build the canonical Factuarea client before using CRM.');
        try {
            $send = static fn () => $client->send($request, $options);
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
        $response = $this->configuration->hooks->afterSuccess(new Hooks\AfterSuccessContext($context), $response);
        $values = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \UnexpectedValueException('The CRM response must be an object.');
        }

        return new CrmResponse($responseType::fromArray((array) $values), $response, $idempotencyKey);
    }
}
