<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService;

use Factuarea\Sdk\Custom\Crm\Documents\DocumentTransport;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\Models\Errors;
use Factuarea\Sdk\SDKConfiguration;
use Factuarea\Sdk\Utils;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Speakeasy\Serializer\DeserializationContext;
use Throwable;

/** Native configured client/auth/hooks. A write is sent once; recovery explicitly selects its original owner receipt. */
final readonly class TicketTransport
{
    private DocumentTransport $reads;
    public function __construct(private SDKConfiguration $config)
    {
        $this->reads = new DocumentTransport($config);
    }
    /** @template T of object
     * @param  array<string,int|string>  $query
     * @param  callable(mixed):T  $decode
     * @return TicketResponse<T>
     */
    public function read(string $operation, string $path, array $query, callable $decode, ?string $version, ?string $profile): TicketResponse
    {
        $response = $this->reads->read($operation, $path, $query, $decode, $version, $profile);

        return new TicketResponse($response->data, $response->rawResponse);
    }
    public function contextFingerprint(): string
    {
        return $this->reads->contextFingerprint();
    }
    /** @return TicketResponse<Model\TicketReceipt> */
    public function write(TicketWriteIntent $intent): TicketResponse
    {
        if (!hash_equals($intent->contextFingerprint, $this->contextFingerprint())) {
            throw new InvalidArgumentException('Ticket recovery requires the original server and credentials.');
        }
        $url = Utils\Utils::generateUrl($this->config->getTemplatedServerUrl(), $intent->path);
        $headers = $this->headers($intent->factuareaVersion, $intent->xActiveProfile);
        $headers['Content-Type'] = 'application/json';
        $headers['Idempotency-Key'] = $intent->idempotencyKey;
        $hook = new Hooks\HookContext($this->config, $this->config->getTemplatedServerUrl(), $intent->operation->value, null, $this->config->securitySource);
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($hook), new Request('POST', $url, $headers, $intent->bodyJson));
        $auth = hash('sha256', $request->getHeaderLine('Authorization')."\0".$request->getHeaderLine('X-API-Key'));
        if ($intent->headersFrozen && ($intent->requestAuthFingerprint === null || !hash_equals($intent->requestAuthFingerprint, $auth))) {
            throw new InvalidArgumentException('Ticket recovery requires the original request credentials.');
        }
        $intent = $intent->freezeHeaders($request->hasHeader('Factuarea-Version') ? $request->getHeaderLine('Factuarea-Version') : null,
            $request->hasHeader('X-Active-Profile') ? $request->getHeaderLine('X-Active-Profile') : null, $auth);
        // Hooks may supply credentials/version, but may not change the original operation, body or key.
        $request = new Request('POST', $url, $request->getHeaders(), $intent->bodyJson);
        $request = $request->withHeader('Content-Type', 'application/json')->withHeader('Idempotency-Key', $intent->idempotencyKey);
        foreach (['Factuarea-Version' => $intent->factuareaVersion, 'X-Active-Profile' => $intent->xActiveProfile] as $name => $value) {
            $request = $value === null ? $request->withoutHeader($name) : $request->withHeader($name, $value);
        }
        try {
            $response = $this->client()->send($request, ['http_errors' => false, 'allow_redirects' => false]);
        } catch (Throwable $error) {
            throw new TicketWriteUnconfirmed($intent, cause: $error);
        }
        if ($response->getStatusCode() !== 200) {
            $error = $this->apiError($response);
            $code = $error instanceof Errors\ErrorThrowable ? $error->container->error->code : null;
            $subcode = $error instanceof Errors\ErrorThrowable ? $error->container->error->subcode : null;
            $uncertain = false;
            foreach ([$code, $subcode] as $reason) {
                if (is_string($reason) && (str_contains($reason, 'unconfirmed') || str_contains($reason, 'revoked') || str_contains($reason, 'authorization_changed'))) {
                    $uncertain = true;
                }
            }
            if ($response->getStatusCode() >= 500 || in_array($response->getStatusCode(), [403, 404], true)
                || ($response->getStatusCode() >= 200 && $response->getStatusCode() < 400) || $uncertain) {
                throw new TicketWriteUnconfirmed($intent, $response, $error);
            }
            throw $error;
        }
        try {
            if (!Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json')) {
                throw new LogicException('The original ticket result requires JSON.');
            }
            $wire = TicketWire::record(json_decode((string) $response->getBody(), true, 64, JSON_THROW_ON_ERROR), ['data']);
            $receipt = Model\TicketReceipt::fromIntent($intent, $wire['data']);
        } catch (Throwable $error) {
            throw new TicketWriteUnconfirmed($intent, $response, $error);
        }

        // Success/error hooks cannot synthesize confirmation or replay an effect.
        return new TicketResponse($receipt, $response);
    }
    /** Same-operation single recovery; no public effect or receipt endpoint is invented.
     * @return TicketResponse<Model\TicketReceipt>
     */
    public function recover(TicketWriteIntent $intent): TicketResponse
    {
        if (!$intent->headersFrozen) {
            throw new InvalidArgumentException('Recovery requires the original sent ticket intent.');
        }

        return $this->write($intent);
    }
    private function apiError(ResponseInterface $response): Throwable
    {
        if (Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json')) {
            try {
                $error = Utils\JSON::createSerializer()->deserialize((string) $response->getBody(), Errors\Error::class, 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                if ($error instanceof Errors\Error) {
                    $error->rawResponse = $response;

                    return $error->toException();
                }
            } catch (Throwable) {
            }
        }

        return new Errors\APIException('The service ticket API rejected the request.', $response->getStatusCode(), (string) $response->getBody(), $response);
    }
    /** @return array<string,string> */
    private function headers(?string $version, ?string $profile): array
    {
        $headers = ['Accept' => 'application/json', 'User-Agent' => $this->config->userAgent];
        $defaults = $this->client()->getConfig('headers');
        if (is_array($defaults)) {
            foreach ($defaults as $name => $value) {
                if (is_string($name) && in_array(strtolower($name), ['factuarea-version', 'x-active-profile'], true)) {
                    $headers[strtolower($name) === 'factuarea-version' ? 'Factuarea-Version' : 'X-Active-Profile'] = is_array($value) ? implode(', ', $value) : (string) $value;
                }
            }
        }
        if ($version !== null) {
            $headers['Factuarea-Version'] = $version;
        }
        if ($profile !== null) {
            $headers['X-Active-Profile'] = strtolower(Wire::uuid($profile));
        }

        return $headers;
    }
    private function client(): ClientInterface
    {
        return $this->config->client ?? throw new LogicException('Build the Factuarea SDK before using service ticket operations.');
    }
}
