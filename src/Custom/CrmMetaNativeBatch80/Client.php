<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\CrmMetaNativeBatch80;

use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OAuthBearerClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\SDKConfiguration;
use Factuarea\Sdk\Utils;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;

/** Explicit human operations; direct native HTTP, no generated retry/refresh/follow-up flow. */
final class Client
{
    private \WeakMap $sent;
    public function __construct(private readonly SDKConfiguration $config)
    {
        $this->sent = new \WeakMap();
    }
    public function prepareCrmMetaOAuth(#[\SensitiveParameter] array|\stdClass $body): Response
    {
        return $this->execute(RequestIntent::fromBody('prepareOAuth', $body));
    }
    public function completeCrmMetaOAuth(#[\SensitiveParameter] array|\stdClass $body): Response
    {
        return $this->execute(RequestIntent::fromBody('completeOAuth', $body));
    }
    public function listCrmMetaConnectionOptions(): Response
    {
        return $this->execute(new RequestIntent('options'));
    }
    public function getCrmMetaMapping(string $connection, string $form): Response
    {
        return $this->execute(new RequestIntent('mapping', null, $connection, $form));
    }
    public function inspectCrmMetaMapping(string $connection, string $form, array|\stdClass $body): Response
    {
        return $this->execute(RequestIntent::fromBody('inspect', $body, $connection, $form));
    }
    public function publishCrmMetaMapping(string $connection, string $form, array|\stdClass $body): Response
    {
        return $this->execute(RequestIntent::fromBody('publishMapping', $body, $connection, $form));
    }
    public function publishCrmMetaConsent(string $connection, array|\stdClass $body): Response
    {
        return $this->execute(RequestIntent::fromBody('publishConsent', $body, $connection));
    }
    public function execute(#[\SensitiveParameter] RequestIntent $intent): Response
    {
        [$method, , $operationId, $mutating] = Contract::ROUTES[$intent->action];
        if ($mutating && isset($this->sent[$intent])) {
            throw new \LogicException('This human command was already dispatched; no automatic replay is available.');
        }
        $client = $this->config->defaultClient ?? $this->config->client ?? throw new \LogicException('Build the native SDK first.');
        $oauth = $client instanceof OAuthBearerClient;
        $token = $oauth ? null : ($this->config->hasSecurity() ? $this->config->getSecurity()?->bearerAuth : null);
        if (!$oauth && (!is_string($token) || preg_match('/^fact_(test|live)_[\x21-\x7e]+$/D', $token) !== 1)) {
            throw new \InvalidArgumentException('Use an actual API key or the native OAuth SDK factory.');
        }
        $url = Utils\Utils::generateUrl($this->config->getTemplatedServerUrl(), $intent->path);
        $hook = new Hooks\HookContext($this->config, $this->config->getTemplatedServerUrl(), $operationId, null, $this->config->securitySource);
        $headers = ['Accept' => 'application/json'] + ($oauth ? [] : ['Authorization' => 'Bearer '.$token]);
        if ($intent->firstWire !== null) {
            $headers['Content-Type'] = 'application/json';
        }
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($hook), new Request($method, $url, $headers, $intent->firstWire ?? ''));
        if (($oauth ? $request->hasHeader('Authorization') : $request->getHeaderLine('Authorization') !== 'Bearer '.$token)) {
            Contract::invalid();
        }
        foreach (['X-API-Key', 'Idempotency-Key', 'X-Company-Id', 'X-Active-Profile'] as $name) {
            if ($request->hasHeader($name)) {
                Contract::invalid();
            }
        }
        // Preserve the selected native URL/body even when a general hook mutates the request.
        $request = new Request($method, $url, $request->getHeaders(), $intent->firstWire ?? '');
        if ($client instanceof IdempotencyClient) {
            $client = new HttpClient($client->getConfig());
        }
        foreach (array_keys($client->getConfig('headers') ?? []) as $name) {
            if (is_string($name) && in_array(strtolower($name), ['authorization', 'x-api-key', 'idempotency-key', 'x-company-id', 'x-active-profile'], true)) {
                Contract::invalid();
            }
        }
        if ($mutating) {
            $this->sent[$intent] = true;
        }
        $requestId = null;
        try {
            $response = $client->send($request, ['http_errors' => false, 'allow_redirects' => false]);
            $requestId = $response->getHeaderLine('X-Request-Id') ?: null;
            $status = $response->getStatusCode();
            $raw = (string) $response->getBody();
            try {
                if (strlen($raw) > 1048576) {
                    throw new \LogicException();
                } $wire = Contract::object(json_decode($raw, false, 32, JSON_THROW_ON_ERROR));
            } catch (\Throwable) {
                throw new MetaException($status, 'invalid_native_response', $requestId, null, $mutating);
            }
            if ($status !== 200) {
                $reason = 'native_rejection';
                if (is_string($wire->error ?? null)) {
                    $candidate = str_starts_with($wire->error, 'meta_oauth_') ? substr($wire->error, 11) : $wire->error;
                    if (in_array($candidate, ['invalid_input', 'invalid_data', 'mapping_conflict', 'signature_invalid', 'permission_missing', 'reauth_required', 'rate_limited', 'retryable', 'unconfirmed', 'receipt_missing', 'unavailable', 'revoked', 'resync_required', 'provider_not_released'], true)) {
                        $reason = $candidate;
                    }
                } elseif (($wire->error ?? null) instanceof \stdClass && is_string($wire->error->code ?? null) && in_array($wire->error->code, ['crm_context_revoked', 'crm_sandbox_blocked', 'crm_access_denied', 'crm_operation_unavailable', 'insufficient_scope', 'api_key_revoked'], true)) {
                    $reason = $wire->error->code;
                }
                $after = $response->getHeaderLine('Retry-After');
                throw new MetaException($status, $reason, $requestId, ctype_digit($after) ? (int) $after : null, $reason === 'unconfirmed' || ($mutating && $status >= 500 && $reason === 'native_rejection'));
            }
            try {
                if (!Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json') || $response->hasHeader('Location')) {
                    Contract::invalid();
                } Contract::projection($intent, $wire);
            } catch (\Throwable) {
                throw new MetaException(200, 'invalid_native_response', $requestId, null, $mutating);
            }

            return new Response($wire, $raw, $status, $requestId);
        } catch (MetaException $e) {
            throw $e;
        } catch (\Throwable) {
            throw new MetaException(null, $mutating ? 'unconfirmed' : 'transport_failure', $requestId, null, $mutating);
        }
    }
}
