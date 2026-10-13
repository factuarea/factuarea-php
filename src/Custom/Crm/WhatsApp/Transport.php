<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\WhatsApp;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OAuthBearerClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\Models\Errors;
use Factuarea\Sdk\SDKConfiguration;
use Factuarea\Sdk\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Speakeasy\Serializer\DeserializationContext;
final class Transport
{
    private \WeakMap $preparations;
    private \WeakMap $intents;
    private array $sent = [];
    private ?string $lastRequestId = null;
    public function __construct(private readonly SDKConfiguration $config)
    {
        $this->preparations = new \WeakMap();
        $this->intents = new \WeakMap();
    }
    public function prepareCrmWhatsAppMessage(string $conversation, string $message, string $purpose, ?array $template = null, ?Preparation $previous = null): Preparation
    {
        $fingerprint = $this->fingerprint();
        $path = $this->path($conversation, $message, 'preparation');
        if (!in_array($purpose, ['contact_request', 'marketing'], true)) {
            throw new \InvalidArgumentException('Invalid native WhatsApp purpose.');
        }
        if ($previous !== null && (($this->preparations[$previous] ?? null) !== $fingerprint || $previous->data['conversation_id'] !== $conversation || $previous->data['message_id'] !== $message || $previous->data['purpose'] !== $purpose)) {
            throw new \InvalidArgumentException('Revalidation requires the same current credential and preparation.');
        }
        if ($previous === null && isset($this->sent[$conversation.'/'.$message])) {
            throw new \LogicException('An attempted send must retain its original effect.');
        }
        $chosen = $previous?->data['template'] ?? Preparation::template($template);
        if ($previous !== null && $template !== null && Preparation::template($template) !== $previous->data['template']) {
            throw new \InvalidArgumentException('Revalidation cannot change the original template.');
        }
        $query = ['purpose' => $purpose];
        if ($chosen !== null) {
            $query['template'] = json_encode($chosen, JSON_THROW_ON_ERROR);
        }
        if ($previous !== null) {
            $query['preparation_token'] = $previous->data['preparation_token'];
        }
        $wire = $this->dispatch('GET', $path, $query, null, 'conversations.send', $fingerprint);
        $p = Preparation::fromWire($wire['data'] ?? null, $conversation, $message);
        if ($p->data['purpose'] !== $purpose || $p->data['template'] !== $chosen || ($previous !== null && $p->data !== $previous->data)) {
            throw new \InvalidArgumentException('Native revalidation changed the sealed preparation.');
        }
        $this->preparations[$p] = $fingerprint;

        return $p;
    }
    public function prepareSend(Preparation $p, ?string $firstWire = null): SendIntent
    {
        $fingerprint = $this->preparations[$p] ?? null;
        if ($fingerprint === null) {
            throw new \InvalidArgumentException('Use a preparation issued to this client.');
        }
        $intent = new SendIntent($p, $firstWire);
        $this->intents[$intent] = $fingerprint;

        return $intent;
    }
    /** @return array{format:string,preparation:array<string,mixed>,first_wire:string,wire_hash:string,context_fingerprint:string,dispatched:bool} */
    public function exportIntent(SendIntent $intent): array
    {
        $fingerprint = $this->intents[$intent] ?? null;
        if ($fingerprint === null) {
            throw new \InvalidArgumentException('Retain the original intent issued to this client.');
        }

        return ['format' => 'factuarea.crm.whatsapp.v1', 'preparation' => $intent->preparation->data, 'first_wire' => $intent->firstWire, 'wire_hash' => $intent->originalWireHash, 'context_fingerprint' => $fingerprint, 'dispatched' => isset($this->sent[$intent->preparation->data['effect_id']])];
    }
    /** Restore is read-only, regardless of the stored dispatched flag. */
    public function restoreOriginal(array $checkpoint): SendIntent
    {
        Preparation::keys($checkpoint, ['format', 'preparation', 'first_wire', 'wire_hash', 'context_fingerprint', 'dispatched']);
        $p = $checkpoint['preparation'];
        if ($checkpoint['format'] !== 'factuarea.crm.whatsapp.v1' || !is_array($p) || !Preparation::uuid($p['conversation_id'] ?? null) || !Preparation::uuid($p['message_id'] ?? null) || !is_string($checkpoint['first_wire']) || !Preparation::digest($checkpoint['wire_hash']) || !Preparation::digest($checkpoint['context_fingerprint']) || !is_bool($checkpoint['dispatched']) || $checkpoint['context_fingerprint'] !== $this->fingerprint()) {
            throw new \InvalidArgumentException('The original checkpoint requires its exact current credential and context.');
        }
        $preparation = Preparation::fromWire($p, $p['conversation_id'], $p['message_id']);
        $intent = new SendIntent($preparation, $checkpoint['first_wire']);
        if ($intent->originalWireHash !== $checkpoint['wire_hash']) {
            throw new \InvalidArgumentException('The original checkpoint wire changed.');
        }
        $this->preparations[$preparation] = $checkpoint['context_fingerprint'];
        $this->intents[$intent] = $checkpoint['context_fingerprint'];
        $this->sent[$p['effect_id']] = true;
        $this->sent[$p['conversation_id'].'/'.$p['message_id']] = true;

        return $intent;
    }
    public function sendCrmWhatsAppMessage(SendIntent $intent): DeliveryReceipt
    {
        $fingerprint = $this->intents[$intent] ?? null;
        if ($fingerprint === null || $fingerprint !== $this->fingerprint()) {
            throw new \InvalidArgumentException('Send requires the original current credential and sealed intent.');
        }
        $p = $intent->preparation->data;
        if (isset($this->sent[$p['effect_id']])) {
            throw new \LogicException('This effect was already dispatched. Query its original.');
        }
        $this->sent[$p['effect_id']] = true;
        $this->sent[$p['conversation_id'].'/'.$p['message_id']] = true;
        try {
            return DeliveryReceipt::fromWire($this->dispatch('POST', $this->path($p['conversation_id'], $p['message_id'], 'send'), [], $intent->firstWire, 'conversations.send', $fingerprint)['data'] ?? null);
        } catch (\Throwable $error) {
            if ($error instanceof Errors\APIException && $error->statusCode < 500) {
                throw $error;
            }
            if ($error instanceof Errors\ErrorThrowable && ($error->container->rawResponse?->getStatusCode() ?? 500) < 500) {
                throw $error;
            }
            throw new SendUnconfirmed($intent, $error, $this->lastRequestId);
        }
    }
    public function getCrmWhatsAppMessageOriginal(SendIntent $intent): OriginalReceipt
    {
        $fingerprint = $this->intents[$intent] ?? null;
        if ($fingerprint === null) {
            throw new \InvalidArgumentException('Retain the original intent issued to this client.');
        }
        $p = $intent->preparation->data;

        return OriginalReceipt::fromWire($this->dispatch('GET', $this->path($p['conversation_id'], $p['message_id'], 'original'), ['original_wire' => $intent->firstWire], null, 'conversations.get', $fingerprint)['data'] ?? null, $intent);
    }
    private function path(string $conversation, string $message, string $action): string
    {
        if (!Preparation::uuid($conversation) || !Preparation::uuid($message)) {
            throw new \InvalidArgumentException('Native conversation and message UUIDv7 are required.');
        }

        return '/crm/conversation/'.$conversation.'/messages/'.$message.'/whatsapp-cloud/'.$action;
    }
    private function token(): string
    {
        $client = $this->config->defaultClient ?? $this->config->client;
        $token = $this->config->hasSecurity() ? $this->config->getSecurity()?->bearerAuth : null;
        if ($client instanceof OAuthBearerClient || !is_string($token) || preg_match('/^fact_(test|live)_[\x21-\x7e]+$/D', $token) !== 1) {
            throw new \InvalidArgumentException('The native catalog currently requires an actual API key for WhatsApp conversation operations.');
        }

        return $token;
    }
    private function fingerprint(): string
    {
        $token = $this->token();
        $url = $this->config->getTemplatedServerUrl();
        $hook = new Hooks\HookContext($this->config, $url, 'conversations.send', null, $this->config->securitySource);
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($hook), new Request('GET', $url, ['Authorization' => 'Bearer '.$token]));

        return $this->requestFingerprint($request);
    }
    private function requestFingerprint(\Psr\Http\Message\RequestInterface $request): string
    {
        return hash('sha256', json_encode([$this->config->getTemplatedServerUrl(), $request->getHeaderLine('Authorization'), $request->getHeaderLine('Factuarea-Version'), $request->getHeaderLine('X-Company-Id'), $request->getHeaderLine('X-Active-Profile')], JSON_THROW_ON_ERROR));
    }
    private function dispatch(string $method, string $path, array $query, ?string $body, string $operation, string $fingerprint): array
    {
        $this->lastRequestId = null;
        if ($fingerprint !== $this->fingerprint()) {
            throw new \InvalidArgumentException('The original current credential changed.');
        }
        $token = $this->token();
        $url = Utils\Utils::generateUrl($this->config->getTemplatedServerUrl(), $path);
        $hook = new Hooks\HookContext($this->config, $this->config->getTemplatedServerUrl(), $operation, null, $this->config->securitySource);
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($hook), new Request($method, $url, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token, 'Content-Type' => 'application/json'], $body ?? ''));
        if ($request->getHeaderLine('Authorization') !== 'Bearer '.$token || $request->hasHeader('X-API-Key') || $request->hasHeader('Idempotency-Key') || $request->hasHeader('X-Company-Id') || $request->hasHeader('X-Active-Profile')) {
            throw new \InvalidArgumentException('Native authentication and preparation context cannot be replaced by a hook.');
        }
        if ($fingerprint !== $this->requestFingerprint($request)) {
            throw new \InvalidArgumentException('The original preparation context changed.');
        }
        $client = $this->config->defaultClient ?? $this->config->client ?? throw new \LogicException('Build the native SDK first.');
        // The native effect UUID replaces HTTP idempotency; reuse the real Guzzle handler without the generic key wrapper.
        if ($client instanceof IdempotencyClient) {
            $client = new Client($client->getConfig());
        }
        $defaults = $client->getConfig('headers') ?? [];
        foreach (array_keys($defaults) as $name) {
            if (is_string($name) && in_array(strtolower($name), ['idempotency-key', 'x-api-key', 'x-company-id', 'x-active-profile'], true)) {
                throw new \InvalidArgumentException('Native effect context cannot be replaced by client defaults.');
            }
        }
        $request = new Request($method, $url, $request->getHeaders(), $body ?? '');
        $response = $client->send($request, ['http_errors' => false, 'allow_redirects' => false, 'query' => $query]);
        $this->lastRequestId = $response->getHeaderLine('X-Request-Id') ?: null;
        $raw = (string) $response->getBody();
        if ($response->getStatusCode() !== 200) {
            try {
                $e = Utils\JSON::createSerializer()->deserialize($raw, Errors\Error::class, 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                if ($e instanceof Errors\Error) {
                    $e->rawResponse = $response;
                    throw $e->toException();
                }
            } catch (Errors\ErrorThrowable $e) {
                throw $e;
            } catch (\Throwable) {
            }
            throw new Errors\APIException('Native WhatsApp request failed.', $response->getStatusCode(), $raw, $response);
        }
        if (!Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json') || $response->hasHeader('Location')) {
            throw new \LogicException('Invalid native WhatsApp response.');
        }
        $wire = json_decode($raw, true, 64, JSON_THROW_ON_ERROR);
        if (!is_array($wire)) {
            throw new \LogicException('Native WhatsApp requires a JSON envelope.');
        }

        return $wire;
    }
}
