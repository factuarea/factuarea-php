<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk;

use Brick\DateTime\LocalDate;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Errors;
use Factuarea\Sdk\Models\Operations;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Speakeasy\Serializer\DeserializationContext;
use Throwable;

/** Native Company/credential authority remains server-owned; every call sends exactly once. */
final class ServiceLevel
{
    public function __construct(public readonly SDKConfiguration $sdkConfig)
    {
    }

    public function getServiceSlaStatus(string $ticket, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Operations\GetServiceSlaStatusResponse
    {
        $response = $this->read('getServiceSlaStatus', '/crm/service-tickets/'.self::uuid($ticket).'/sla', [], $factuareaVersion, $xActiveProfile);
        $body = $this->decode($response, Operations\GetServiceSlaStatusResponseBody::class);

        return new Operations\GetServiceSlaStatusResponse($response->getHeaderLine('Content-Type'), $response->getStatusCode(), $response, $body, $response->getHeaders());
    }

    public function getServiceSlaHistory(string $ticket, int $page = 1, int $perPage = 25, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Operations\GetServiceSlaHistoryResponse
    {
        $response = $this->read('getServiceSlaHistory', '/crm/service-tickets/'.self::uuid($ticket).'/sla/history', self::pagination($page, $perPage), $factuareaVersion, $xActiveProfile);
        $body = $this->decode($response, Operations\GetServiceSlaHistoryResponseBody::class);

        return new Operations\GetServiceSlaHistoryResponse($response->getHeaderLine('Content-Type'), $response->getStatusCode(), $response, $body, $response->getHeaders());
    }

    public function listServiceCalendars(int $page = 1, int $perPage = 25, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Operations\ListServiceCalendarsResponse
    {
        $response = $this->read('listServiceCalendars', '/crm/service-calendars', self::pagination($page, $perPage), $factuareaVersion, $xActiveProfile);
        $body = $this->decode($response, Operations\ListServiceCalendarsResponseBody::class);

        return new Operations\ListServiceCalendarsResponse($response->getHeaderLine('Content-Type'), $response->getStatusCode(), $response, $body, $response->getHeaders());
    }

    public function configureServiceSla(string $ticket, Components\ConfigureServiceSlaRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Operations\ServiceLevelWriteResponse
    {
        return $this->write('configureServiceSla', self::uuid($ticket), $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }

    public function pauseServiceSla(string $ticket, Components\PauseServiceSlaRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Operations\ServiceLevelWriteResponse
    {
        return $this->write('pauseServiceSla', self::uuid($ticket), $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }

    public function resumeServiceSla(string $ticket, Components\ResumeServiceSlaRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Operations\ServiceLevelWriteResponse
    {
        return $this->write('resumeServiceSla', self::uuid($ticket), $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }

    public function updateServiceCalendar(Components\UpdateServiceCalendarRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Operations\ServiceLevelWriteResponse
    {
        return $this->write('updateServiceCalendar', null, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }

    /** Explicit single recovery request to the same operation. The server reads its reserved immutable receipt. */
    public function recover(Operations\ServiceLevelWriteIntent $intent): Operations\ServiceLevelWriteResponse
    {
        if (! $intent->headersFrozen || ! hash_equals($intent->contextFingerprint, $this->contextFingerprint())) {
            throw new InvalidArgumentException('La recuperación SLA requiere el servidor y la credencial originales.');
        }

        return $this->sendIntent($intent);
    }

    private function write(string $operation, ?string $ticket,
        Components\ConfigureServiceSlaRequest|Components\PauseServiceSlaRequest|Components\ResumeServiceSlaRequest|Components\UpdateServiceCalendarRequest $body,
        string $key, ?LocalDate $version, ?string $profile): Operations\ServiceLevelWriteResponse
    {
        foreach (['id', 'cycleId', 'policyId', 'calendarId'] as $field) {
            if (property_exists($body, $field) && $body->{$field} !== null) {
                self::uuid($body->{$field});
            }
        }
        if ($profile !== null) {
            self::uuid($profile);
        }
        $intent = Operations\ServiceLevelWriteIntent::create($operation, $ticket, $body, $key, $version === null ? null : (string) $version, $profile, $this->contextFingerprint());

        return $this->sendIntent($intent);
    }

    private function sendIntent(Operations\ServiceLevelWriteIntent $intent): Operations\ServiceLevelWriteResponse
    {
        $suffix = match ($intent->operation) {
            'configureServiceSla' => '', 'pauseServiceSla' => '/pause', 'resumeServiceSla' => '/resume', 'updateServiceCalendar' => null,
            default => throw new InvalidArgumentException('Operación SLA desconocida.'),
        };
        $path = $suffix === null ? '/crm/service-calendars' : '/crm/service-tickets/'.self::uuid($intent->ticket).'/sla'.$suffix;
        $method = in_array($intent->operation, ['pauseServiceSla', 'resumeServiceSla'], true) ? 'POST' : 'PUT';
        $headers = $this->headers($intent->factuareaVersion, $intent->xActiveProfile);
        $headers['Content-Type'] = 'application/json';
        $headers['Idempotency-Key'] = $intent->idempotencyKey;
        $context = $this->hookContext($intent->operation);
        $request = new Request($method, Utils\Utils::generateUrl($this->sdkConfig->getTemplatedServerUrl(), $path), $headers, $intent->bodyJson);
        $request = $this->sdkConfig->hooks->beforeRequest(new Hooks\BeforeRequestContext($context), $request);
        $requestAuth = hash('sha256', $request->getHeaderLine('Authorization')."\0".$request->getHeaderLine('X-API-Key'));
        if ($intent->headersFrozen && ($intent->requestAuthFingerprint === null || ! hash_equals($intent->requestAuthFingerprint, $requestAuth))) {
            throw new InvalidArgumentException('La recuperación SLA requiere la credencial original del request.');
        }
        $intent = $intent->freezeHeaders($request->hasHeader('Factuarea-Version') ? $request->getHeaderLine('Factuarea-Version') : null,
            $request->hasHeader('X-Active-Profile') ? $request->getHeaderLine('X-Active-Profile') : null, $requestAuth);
        // Hooks may decorate headers; the original operation, body, key and effective Company/version never drift.
        $request = new Request($method, Utils\Utils::generateUrl($this->sdkConfig->getTemplatedServerUrl(), $path), $request->getHeaders(), $intent->bodyJson);
        $request = $request->withHeader('Idempotency-Key', $intent->idempotencyKey);
        foreach (['Factuarea-Version' => $intent->factuareaVersion, 'X-Active-Profile' => $intent->xActiveProfile] as $name => $value) {
            $request = $value === null ? $request->withoutHeader($name) : $request->withHeader($name, $value);
        }
        try {
            $response = $this->client()->send($request, ['http_errors' => false, 'allow_redirects' => false]);
        } catch (Throwable $error) {
            // No after-error hook may replace an ambiguous write with a synthetic success or a fresh dispatch.
            throw new Errors\ServiceLevelWriteUnconfirmed($intent, cause: $error);
        }
        if ($response->getStatusCode() !== 200) {
            $error = $this->apiError($response);
            $unconfirmed = $error instanceof Errors\ErrorThrowable && $error->container->error->subcode === 'service_level_result_unconfirmed';
            if ($response->getStatusCode() >= 500 || $unconfirmed || ($response->getStatusCode() >= 200 && $response->getStatusCode() < 400)) {
                throw new Errors\ServiceLevelWriteUnconfirmed($intent, $response, $error);
            }
            throw $error;
        }
        try {
            $this->requireJson($response);
            $wire = json_decode((string) $response->getBody(), true, 64, JSON_THROW_ON_ERROR);
            if (! is_array($wire) || ! is_array($wire['data'] ?? null) || count($wire['data']) !== 2 || ! is_string($wire['data']['id'] ?? null)
                || ! is_int($wire['data']['version'] ?? null) || $wire['data']['version'] < 1) {
                throw new LogicException('El resultado SLA requiere su recibo original tipado.');
            }
            $id = self::uuid($wire['data']['id']);
            if ($intent->expectedResultId !== null && $id !== strtolower($intent->expectedResultId)) {
                throw new LogicException('La identidad del recibo SLA no coincide con la intención original.');
            }
            $body = $this->decode($response, Operations\ServiceLevelWriteResponseBody::class);
        } catch (Throwable $error) {
            throw new Errors\ServiceLevelWriteUnconfirmed($intent, $response, $error);
        }

        // A hook cannot replace the native immutable receipt returned above.
        return new Operations\ServiceLevelWriteResponse($response->getHeaderLine('Content-Type'), $response->getStatusCode(), $response, $body, $response->getHeaders());
    }

    /** @param array<string,int> $query */
    private function read(string $operation, string $path, array $query, ?LocalDate $version, ?string $profile): ResponseInterface
    {
        $context = $this->hookContext($operation);
        $url = Utils\Utils::generateUrl($this->sdkConfig->getTemplatedServerUrl(), $path);
        $request = new Request('GET', $url, $this->headers($version === null ? null : (string) $version, $profile));
        $request = $this->sdkConfig->hooks->beforeRequest(new Hooks\BeforeRequestContext($context), $request);
        $response = $this->client()->send($request, ['http_errors' => false, 'allow_redirects' => false, 'query' => $query]);
        if ($response->getStatusCode() !== 200) {
            throw $this->apiError($response);
        }

        return $response;
    }

    /** @template T of object
     * @param class-string<T> $type
     * @return T */
    private function decode(ResponseInterface $response, string $type): object
    {
        $this->requireJson($response);
        try {
            $body = Utils\JSON::createSerializer()->deserialize((string) $response->getBody(), $type, 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
            if (! $body instanceof $type) {
                throw new LogicException('La respuesta SLA no coincide con su contrato tipado.');
            }

            return $body;
        } catch (Throwable $error) {
            throw new Errors\APIException('La respuesta SLA no coincide con su contrato tipado.', $response->getStatusCode(), (string) $response->getBody(), $response);
        }
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
                // Preserve the original status/body even when the server did not return the native error envelope.
            }
        }

        return new Errors\APIException('La API SLA rechazó la solicitud.', $response->getStatusCode(), (string) $response->getBody(), $response);
    }

    private function requireJson(ResponseInterface $response): void
    {
        if (! Utils\Utils::matchContentType($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new Errors\APIException('La respuesta SLA requiere JSON.', $response->getStatusCode(), (string) $response->getBody(), $response);
        }
    }

    /** @return array<string,string> */
    private function headers(?string $version, ?string $profile): array
    {
        $headers = ['Accept' => 'application/json', 'User-Agent' => $this->sdkConfig->userAgent];
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
            $headers['X-Active-Profile'] = self::uuid($profile);
        }

        return $headers;
    }

    private function contextFingerprint(): string
    {
        $authorityHeaders = [];
        $defaults = $this->client()->getConfig('headers');
        if (is_array($defaults)) {
            foreach ($defaults as $name => $value) {
                if (is_string($name) && in_array(strtolower($name), ['authorization', 'x-api-key', 'x-active-profile', 'factuarea-version'], true)) {
                    $authorityHeaders[strtolower($name)] = $value;
                }
            }
        }
        ksort($authorityHeaders);

        return hash('sha256', $this->sdkConfig->getTemplatedServerUrl()."\0".serialize([$this->sdkConfig->hasSecurity() ? $this->sdkConfig->getSecurity() : null, $authorityHeaders]));
    }

    private function hookContext(string $operation): Hooks\HookContext
    {
        return new Hooks\HookContext($this->sdkConfig, $this->sdkConfig->getTemplatedServerUrl(), $operation, null, $this->sdkConfig->securitySource);
    }

    private function client(): \GuzzleHttp\ClientInterface
    {
        return $this->sdkConfig->client ?? throw new LogicException('Construye el SDK con Factuarea::builder() antes de usar ServiceLevel.');
    }

    private static function uuid(?string $value): string
    {
        if ($value === null || preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD', $value) !== 1) {
            throw new InvalidArgumentException('El identificador debe ser un UUIDv7 nativo.');
        }

        return strtolower($value);
    }

    /** @return array{page:int,per_page:int} */
    private static function pagination(int $page, int $perPage): array
    {
        if ($page < 1 || $page > 1000000 || $perPage < 1 || $perPage > 100) {
            throw new InvalidArgumentException('La página SLA debe estar entre 1 y 1000000 y su tamaño entre 1 y 100.');
        }

        return ['page' => $page, 'per_page' => $perPage];
    }
}
