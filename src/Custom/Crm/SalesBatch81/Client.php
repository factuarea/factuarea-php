<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OAuthBearerClient;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalReceipt;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\SDKConfiguration;
use Factuarea\Sdk\Utils;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/** Native owner routes on the existing SDK HTTP/authentication client. No generated retries or credential copy. */
final readonly class Client
{
    public const ROUTES = [
        'leadCreate' => ['POST', '/crm/leads', 'crm_leads.create', 'lead'],
        'opportunityCreate' => ['POST', '/crm/opportunity', 'crm_opportunities.create', 'opportunity'],
        'activityCreate' => ['POST', '/crm/crmactivity', 'crm_activities.create', 'activity'],
        'leadPatch' => ['PATCH', '/crm/leads/{lead}', 'crm_leads.update', 'lead'],
    ];
    public function __construct(private SDKConfiguration $config)
    {
    }
    public function prepareLead(CreateLeadRequest $request, string $key): WriteIntent
    {
        return $this->prepare('leadCreate', $request->toArray(), $key);
    }
    public function prepareOpportunity(CreateOpportunityRequest $request, string $key): WriteIntent
    {
        return $this->prepare('opportunityCreate', $request->toArray(), $key);
    }
    public function prepareActivity(CreateActivityRequest $request, string $key): WriteIntent
    {
        return $this->prepare('activityCreate', $request->toArray(), $key);
    }
    public function prepareLeadPatch(string $lead, LeadPatchRequest $request, string $key): WriteIntent
    {
        return $this->prepare('leadPatch', $request->toArray(), $key, Wire::leadUuid($lead));
    }
    private function prepare(string $action, array $body, string $key, ?string $lead = null): WriteIntent
    {
        $path = self::ROUTES[$action][1];
        if ($lead !== null) {
            $path = str_replace('{lead}', $lead, $path);
        }

        return new WriteIntent($action, $path, $key, Wire::encode($body), $this->contextFingerprint());
    }
    public function dispatch(#[\SensitiveParameter] WriteIntent $intent): SalesResult
    {
        [$method,,$operation,$kind] = self::ROUTES[$intent->action];
        $intent->claim($this->contextFingerprint());
        $response = $this->send($method, $intent->path, $operation, [], ['Idempotency-Key' => $intent->key], $intent->firstBody, true);
        if (!in_array($response->getStatusCode(), [200, 201], true)) {
            throw $this->failure($response, true);
        }
        try {
            $result = SalesResult::decode($kind, $this->json($response), $response->getStatusCode(), $this->requestId($response));
        } catch (\Throwable) {
            throw new WriteUnconfirmed($response->getStatusCode(), 'invalid_native_response', requestId: $this->requestId($response), unconfirmed: true);
        }
        if (!$result->confirmed) {
            throw new WriteUnconfirmed($response->getStatusCode(), 'crm_effect_unconfirmed', requestId: $this->requestId($response), unconfirmed: true);
        }

        return $result;
    }
    public function recover(#[\SensitiveParameter] WriteIntent $intent): OriginalReceipt
    {
        $intent->assertContext($this->contextFingerprint());

        return $this->recoverOriginal($intent->original());
    }
    /** A durable host journal supplies only the exact original key/body; the native server proves current credential ancestry and C7. */
    public function recoverOriginal(#[\SensitiveParameter] OriginalIntent $original): OriginalReceipt
    {
        $response = $this->send('GET', $original->path, OriginalIntent::SCOPES[$original->operation]['operation'],
            ['original_body_sha256' => $original->originalBodySha256], ['Idempotency-Key' => $original->idempotencyKey]);
        if ($response->getStatusCode() !== 200) {
            throw $this->failure($response, false);
        }
        try {
            return OriginalReceipt::fromWire(json_decode($this->json($response), true, 64, JSON_THROW_ON_ERROR), $original);
        } catch (\Throwable) {
            throw new CrmFailure(200, 'invalid_native_original', requestId: $this->requestId($response));
        }
    }
    public function listLeads(int $limit = 25, ?string $cursor = null): LeadPage
    {
        if ($limit < 1 || $limit > 100 || ($cursor !== null && ($cursor === '' || strlen($cursor) > 4096))) {
            throw new \InvalidArgumentException('Use the native Lead limit and original opaque cursor.');
        }
        $query = ['limit' => $limit];
        if ($cursor !== null) {
            $query['cursor'] = $cursor;
        }
        $response = $this->send('GET', '/crm/leads', 'crm_leads.index', $query);
        if ($response->getStatusCode() !== 200) {
            throw $this->failure($response, false);
        }
        try {
            return LeadPage::decode($this->json($response));
        } catch (\Throwable) {
            throw new CrmFailure(200, 'invalid_native_page', requestId: $this->requestId($response));
        }
    }
    /** Full traversal requires an explicit budget; a repeated cursor or exhausted budget is a failure, never silent completion. */
    public function leads(int $maximumPages, int $limit = 25): \Generator
    {
        if ($maximumPages < 1) {
            throw new \InvalidArgumentException('An explicit page budget is required.');
        }
        $cursor = null;
        $seen = [];
        for ($number = 0; $number < $maximumPages; $number++) {
            $page = $this->listLeads($limit, $cursor);
            foreach ($page->items as $item) {
                yield $item;
            }
            if (!$page->hasMore) {
                return;
            }
            $cursor = $page->nextCursor;
            if (isset($seen[$cursor])) {
                throw new CrmFailure(200, 'pagination_conflict');
            }
            $seen[$cursor] = true;
        }
        throw new CrmFailure(200, 'pagination_budget_exhausted');
    }
    private function client(): ClientInterface
    {
        return $this->config->defaultClient ?? $this->config->client ?? throw new \LogicException('Build the native SDK first.');
    }
    private function contextFingerprint(): string
    {
        $client = $this->client();
        $credential = $client instanceof OAuthBearerClient ? 'native-oauth-dispatch' : $this->apiKey();

        return hash('sha256', $this->config->getTemplatedServerUrl()."\0".spl_object_id($client)."\0".$credential);
    }
    private function apiKey(): string
    {
        $token = $this->config->hasSecurity() ? $this->config->getSecurity()?->bearerAuth : null;
        if (!is_string($token) || preg_match('/^fact_(test|live)_[\x21-\x7e]+$/D', $token) !== 1) {
            throw new \InvalidArgumentException('Use the actual API-key SDK or native OAuth factory.');
        }

        return $token;
    }
    private function send(string $method, string $path, string $operation, array $query = [], array $headers = [], ?string $body = null, bool $write = false): ResponseInterface
    {
        $client = $this->client();
        $oauth = $client instanceof OAuthBearerClient;
        $token = $oauth ? null : $this->apiKey();
        foreach (array_keys($client->getConfig('headers') ?? []) as $name) {
            if (is_string($name) && in_array(strtolower($name), ['authorization', 'x-api-key', 'idempotency-key', 'x-company-id'], true)) {
                throw new \InvalidArgumentException('Native SDK credentials and original key cannot be overridden by client defaults.');
            }
        }
        $url = Utils\Utils::generateUrl($this->config->getTemplatedServerUrl(), $path);
        $fixed = ['Accept' => 'application/json'] + ($oauth ? [] : ['Authorization' => 'Bearer '.$token]) + $headers;
        if ($body !== null) {
            $fixed['Content-Type'] = 'application/json';
        }
        $hook = new Hooks\HookContext($this->config, $this->config->getTemplatedServerUrl(), $operation, null, $this->config->securitySource);
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($hook), new Request($method, $url, $fixed, $body ?? ''));
        if (($oauth ? $request->hasHeader('Authorization') : $request->getHeaderLine('Authorization') !== 'Bearer '.$token)
            || $request->hasHeader('X-API-Key') || $request->hasHeader('X-Company-Id')) {
            throw new \InvalidArgumentException('Native authentication cannot be replaced by a request hook.');
        }
        $request = new Request($method, $url, $request->getHeaders(), $body ?? '');
        foreach ($fixed as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        if (!isset($headers['Idempotency-Key'])) {
            $request = $request->withoutHeader('Idempotency-Key');
        }
        try {
            return $client->send($request, ['http_errors' => false, 'allow_redirects' => false, 'query' => $query]);
        } catch (\Throwable) {
            if ($write) {
                throw new WriteUnconfirmed(null, 'transport_failure', unconfirmed: true);
            }
            throw new CrmFailure(null, 'transport_failure');
        }
    }
    private function json(ResponseInterface $response): string
    {
        $raw = (string) $response->getBody();
        if (!Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json') || strlen($raw) > 1048576) {
            throw new \UnexpectedValueException('The native result requires bounded JSON.');
        }

        return $raw;
    }
    private function requestId(ResponseInterface $response, mixed $errorRequestId = null): ?string
    {
        return self::safe($response->getHeaderLine('X-Request-Id'), '/^[A-Za-z0-9_.:-]{1,255}$/D')
            ?? self::safe($errorRequestId, '/^[A-Za-z0-9_.:-]{1,255}$/D');
    }
    private static function safe(mixed $value, string $pattern): ?string
    {
        return is_string($value) && preg_match($pattern, $value) === 1 ? $value : null;
    }
    private function failure(ResponseInterface $response, bool $write): CrmFailure
    {
        $status = $response->getStatusCode();
        $error = null;
        try {
            $root = json_decode($this->json($response), false, 64, JSON_THROW_ON_ERROR);
            $error = $root->error ?? null;
        } catch (\Throwable) {
        }
        $code = self::safe($error->code ?? null, '/^[a-z][a-z0-9_]{0,99}$/D') ?? 'native_rejection';
        $subcode = self::safe($error->subcode ?? null, '/^[a-z][a-z0-9_]{0,99}$/D');
        $param = self::safe($error->param ?? null, '/^[A-Za-z][A-Za-z0-9_.-]{0,127}$/D');
        $after = self::safe($response->getHeaderLine('Retry-After'), '/^[\x20-\x7e]{1,128}$/D');
        $uncertain = $write && ($status >= 500 || ($status >= 200 && $status < 400)
            || str_contains($code, 'unconfirmed') || str_contains($subcode ?? '', 'unconfirmed') || $code === 'crm_context_revoked');
        $class = match (true) {
            $status === 422 => ValidationFailure::class,
            in_array($status,[401, 403, 404],true) => PermissionFailure::class,
            $status === 429 => RateLimitFailure::class,
            $uncertain => WriteUnconfirmed::class,
            $status === 409 => ConflictFailure::class,
            default => CrmFailure::class,
        };

        return new $class(
            $status,
            $code,
            $subcode,
            $param,
            $this->requestId($response, $error->request_id ?? null),
            $after,
            $uncertain,
        );
    }
}
