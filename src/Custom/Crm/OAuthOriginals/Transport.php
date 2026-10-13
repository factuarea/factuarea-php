<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\OAuthOriginals;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\Models\Errors;
use Factuarea\Sdk\SDKConfiguration;
use Factuarea\Sdk\Utils;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Speakeasy\Serializer\DeserializationContext;
use Throwable;
/** Direct read-only transport: no generated retry loop, redirects, after-error replay or mutation. */
final readonly class Transport
{
    public function __construct(private SDKConfiguration $config)
    {
    }
    public function credentialContext(): CredentialContext
    {
        return CredentialContext::fromWire($this->read(CredentialContext::OPERATION, '/crm/credential-context', [], [], true)['data'] ?? null);
    }
    public function recover(OriginalIntent $intent): OriginalReceipt
    {
        $wire = $this->read(OriginalIntent::SCOPES[$intent->operation]['operation'], $intent->path,
            ['original_body_sha256' => $intent->originalBodySha256], ['Idempotency-Key' => $intent->idempotencyKey]);

        return OriginalReceipt::fromWire($wire, $intent);
    }
    public function getLeadCreateOriginal(string $key, string $firstBody): OriginalReceipt
    {
        return $this->recover(new OriginalIntent('leadCreate', $key, $firstBody));
    }
    public function getOpportunityCreateOriginal(string $key, string $firstBody): OriginalReceipt
    {
        return $this->recover(new OriginalIntent('opportunityCreate', $key, $firstBody));
    }
    public function getOpportunityStageOriginal(string $opportunity, string $key, string $firstBody): OriginalReceipt
    {
        return $this->recover(new OriginalIntent('opportunityStage', $key, $firstBody, $opportunity));
    }
    public function getActivityCreateOriginal(string $key, string $firstBody): OriginalReceipt
    {
        return $this->recover(new OriginalIntent('activityCreate', $key, $firstBody));
    }
    /** @param array<string,string> $query @param array<string,string> $headers @return array<string,mixed> */
    private function read(string $operation, string $path, array $query, array $headers, bool $oauthOnly = false): array
    {
        $client = $this->config->defaultClient ?? $this->config->client ?? throw new LogicException('Build the native SDK before reading an original.');
        $nativeOAuth = $client instanceof OAuthBearerClient;
        $token = $nativeOAuth ? null : ($this->config->hasSecurity() ? $this->config->getSecurity()?->bearerAuth : null);
        if (!$nativeOAuth && (!is_string($token) || preg_match('/^[\x21-\x7e]+$/D', $token) !== 1
            || ($oauthOnly && (str_starts_with($token, 'fact_test_') || str_starts_with($token, 'fact_live_'))))) {
            throw new InvalidArgumentException('This operation requires its actual native bearer credential.');
        }
        $url = Utils\Utils::generateUrl($this->config->getTemplatedServerUrl(), $path);
        $hook = new Hooks\HookContext($this->config, $this->config->getTemplatedServerUrl(), $operation, null, $this->config->securitySource);
        $authHeaders = $nativeOAuth ? [] : ['Authorization' => 'Bearer '.$token];
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($hook), new Request('GET', $url,
            ['Accept' => 'application/json', 'User-Agent' => $this->config->userAgent] + $authHeaders + $headers));
        if (($nativeOAuth ? $request->hasHeader('Authorization') : $request->getHeaderLine('Authorization') !== 'Bearer '.$token) || $request->hasHeader('X-API-Key')) {
            throw new InvalidArgumentException('Native authentication cannot be replaced by a request hook.');
        }
        $request = new Request('GET', $url, $request->getHeaders());
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        $response = $client->send($request, ['http_errors' => false, 'allow_redirects' => false, 'query' => $query]);
        if ($response->getStatusCode() !== 200) {
            throw $this->apiError($response);
        }
        if (!Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new Errors\APIException('The native original requires JSON.', 200, (string) $response->getBody(), $response);
        }
        $wire = json_decode((string) $response->getBody(), true, 64, JSON_THROW_ON_ERROR);
        if (!is_array($wire)) {
            throw new LogicException('The native original requires a JSON envelope.');
        }

        return $wire;
    }
    private function apiError(ResponseInterface $response): Throwable
    {
        try {
            $error = Utils\JSON::createSerializer()->deserialize((string) $response->getBody(), Errors\Error::class, 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
            if ($error instanceof Errors\Error) {
                $error->rawResponse = $response;

                return $error->toException();
            }
        } catch (Throwable) {
        }

        return new Errors\APIException('The native original request failed.', $response->getStatusCode(), (string) $response->getBody(), $response);
    }
}
