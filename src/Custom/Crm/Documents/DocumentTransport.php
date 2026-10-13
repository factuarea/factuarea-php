<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\SDKConfiguration;
use Factuarea\Sdk\Utils;
use Factuarea\Sdk\Models\Errors;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Speakeasy\Serializer\DeserializationContext;
use Throwable;

/** Reuses the configured native HTTP/authentication client. No second client or automatic write retry. */
final readonly class DocumentTransport
{
    public function __construct(private SDKConfiguration $config)
    {
    }
    /**
     * @template T of object
     * @param  callable(mixed):T  $decode
     * @param  array<string,int|string|bool>  $query
     * @return DocumentResponse<T>
     */
    public function read(string $operation, string $path, array $query, callable $decode, ?string $version = null, ?string $profile = null): DocumentResponse
    {
        $response = $this->readResponse($operation, $path, $query, $version, $profile);
        try {
            $data = $decode($this->json($response));
        } catch (Throwable $error) {
            throw new Errors\APIException('The document response does not match the current typed v1 contract.', $response->getStatusCode(), (string) $response->getBody(), $response);
        }

        return new DocumentResponse($data, $response);
    }
    /** @return DocumentResponse<StreamInterface> */
    public function download(string $path, ?int $documentVersion, ?string $version = null, ?string $profile = null): DocumentResponse
    {
        $query = $documentVersion === null ? [] : ['document_version' => Wire::integer($documentVersion)];
        $response = $this->readResponse('crm_documents.download', $path, $query, $version, $profile, true);

        return new DocumentResponse($response->getBody(), $response);
    }
    /** @return DocumentResponse<Model\LibraryReceipt|Model\OpportunityDocumentLink|Model\UnlinkedDocument> */
    public function write(DocumentWriteIntent $intent): DocumentResponse
    {
        if (!hash_equals($intent->contextFingerprint, $this->contextFingerprint())) {
            throw new InvalidArgumentException('Document recovery requires the original server and credentials.');
        }
        $url = Utils\Utils::generateUrl($this->config->getTemplatedServerUrl(), $intent->path);
        $headers = $this->headers($intent->factuareaVersion, $intent->xActiveProfile);
        $headers['Content-Type'] = 'application/json';
        $headers['Idempotency-Key'] = $intent->idempotencyKey;
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($this->hook($intent->operation->value)), new Request($intent->method, $url, $headers, $intent->bodyJson));
        $auth = hash('sha256', $request->getHeaderLine('Authorization')."\0".$request->getHeaderLine('X-API-Key'));
        if ($intent->headersFrozen && ($intent->requestAuthFingerprint === null || !hash_equals($intent->requestAuthFingerprint, $auth))) {
            throw new InvalidArgumentException('Document recovery requires the original request credentials.');
        }
        $intent = $intent->freezeHeaders($request->hasHeader('Factuarea-Version') ? $request->getHeaderLine('Factuarea-Version') : null, $request->hasHeader('X-Active-Profile') ? $request->getHeaderLine('X-Active-Profile') : null, $auth);
        $request = new Request($intent->method, $url, $request->getHeaders(), $intent->bodyJson);
        $request = $request->withHeader('Content-Type', 'application/json')->withHeader('Idempotency-Key', $intent->idempotencyKey);
        foreach (['Factuarea-Version' => $intent->factuareaVersion, 'X-Active-Profile' => $intent->xActiveProfile] as $name => $value) {
            $request = $value === null ? $request->withoutHeader($name) : $request->withHeader($name, $value);
        }
        try {
            $response = $this->client()->send($request, ['http_errors' => false, 'allow_redirects' => false]);
        } catch (Throwable $error) {
            throw new DocumentWriteUnconfirmed($intent, cause: $error);
        }
        if ($response->getStatusCode() !== $intent->operation->status()) {
            $error = $this->apiError($response);
            $subcode = $error instanceof Errors\ErrorThrowable ? $error->container->error->subcode : null;
            if ($response->getStatusCode() >= 500 || in_array($response->getStatusCode(), [403, 404], true)
                || ($response->getStatusCode() >= 200 && $response->getStatusCode() < 400)
                || (is_string($subcode) && (str_contains($subcode, 'unconfirmed') || str_contains($subcode, 'revoked')))) {
                throw new DocumentWriteUnconfirmed($intent, $response, $error);
            }
            throw $error;
        }
        try {
            $receipt = DocumentReceiptDecoder::decode($intent, $this->json($response));
        } catch (Throwable $error) {
            throw new DocumentWriteUnconfirmed($intent, $response, $error);
        }

        // No after-error/after-success hook may fabricate confirmation or replay a mutation.
        return new DocumentResponse($receipt, $response);
    }
    /** Explicit same-operation single recovery. The backend selects the existing immutable native receipt.
     * @return DocumentResponse<Model\LibraryReceipt|Model\OpportunityDocumentLink|Model\UnlinkedDocument>
     */
    public function recover(DocumentWriteIntent $intent): DocumentResponse
    {
        if (!$intent->headersFrozen) {
            throw new InvalidArgumentException('Recovery requires the original sent intent.');
        }

        return $this->write($intent);
    }
    public function contextFingerprint(): string
    {
        $headers = [];
        $defaults = $this->client()->getConfig('headers');
        if (is_array($defaults)) {
            foreach ($defaults as $name => $value) {
                if (is_string($name) && in_array(strtolower($name), ['authorization', 'x-api-key', 'x-active-profile', 'factuarea-version'], true)) {
                    $headers[strtolower($name)] = $value;
                }
            }
        }
        ksort($headers);

        return hash('sha256', $this->config->getTemplatedServerUrl()."\0".serialize([$this->config->hasSecurity() ? $this->config->getSecurity() : null, $headers]));
    }
    /** @param array<string,int|string|bool> $query */
    private function readResponse(string $operation, string $path, array $query, ?string $version, ?string $profile, bool $stream = false): ResponseInterface
    {
        $url = Utils\Utils::generateUrl($this->config->getTemplatedServerUrl(), $path);
        $request = $this->config->hooks->beforeRequest(new Hooks\BeforeRequestContext($this->hook($operation)), new Request('GET', $url, $this->headers($version, $profile)));
        $request = new Request('GET', $url, $request->getHeaders());
        $response = $this->client()->send($request, ['http_errors' => false, 'allow_redirects' => false, 'query' => $query, 'stream' => $stream]);
        if ($response->getStatusCode() !== 200) {
            throw $this->apiError($response);
        }

        return $response;
    }
    private function json(ResponseInterface $response): mixed
    {
        if (!Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new LogicException('The document response requires JSON.');
        }
        $wire = Wire::record(json_decode((string) $response->getBody(), true, 64, JSON_THROW_ON_ERROR), ['data']);

        return $wire['data'];
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

        return new Errors\APIException('The document API rejected the request.', $response->getStatusCode(), (string) $response->getBody(), $response);
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
        }if ($profile !== null) {
            $headers['X-Active-Profile'] = strtolower(Wire::uuid($profile));
        }

        return $headers;
    }
    private function client(): ClientInterface
    {
        return $this->config->client ?? throw new LogicException('Build the Factuarea SDK before using document operations.');
    }
    private function hook(string $operation): Hooks\HookContext
    {
        return new Hooks\HookContext($this->config, $this->config->getTemplatedServerUrl(), $operation, null, $this->config->securitySource);
    }
}
