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

/** Uses the SDK's configured HTTP client, security and hooks; CRM errors are normalized to sanitized typed failures. */
final readonly class Transport implements \Factuarea\Sdk\Custom\Crm\Sequence\OriginalJsonTransportInterface
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
        if ($body !== null) {
            $body->validate();
        }
        $response = $this->execute($method, $path, $operationId, $responseType, $query,
            $body === null ? null : json_encode($body, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION),
            $idempotencyKey, $hasEffect, $headers, false, false);
        if (! $response instanceof CrmResponse) {
            throw new \LogicException('A canonical CRM JSON response is required.');
        }

        return $response;
    }

    /** @template T of WireModel
     * @param class-string<T> $responseType
     * @param array<string,string> $headers
     * @return CrmResponse<T> */
    public function requestOriginalJson(string $path, string $operationId, string $responseType,
        string $originalWire, string $originalKey, array $headers = []): CrmResponse
    {
        if (! in_array($operationId, ['automation.crm_sequence_save', 'automation.crm_sequence_enrol'], true)) {
            throw new \InvalidArgumentException('The original JSON protocol is qualified only for Sequence save/enrol.');
        }
        \Factuarea\Sdk\Custom\Crm\Sequence\WireCodec::key($originalKey);
        \Factuarea\Sdk\Custom\Crm\Sequence\WireCodec::decode($originalWire);
        $response = $this->execute('POST', $path, $operationId, $responseType, [], $originalWire,
            $originalKey, true, $headers, true, false);
        if (! $response instanceof CrmResponse) {
            throw new \LogicException('An original CRM JSON response is required.');
        }

        return $response;
    }

    /**
     * Native Booking retains its exact prepared/public intent. Its recovery is
     * POST with original_wire_body, but does not perform another mutation.
     *
     * @template T of WireModel
     * @param  class-string<T>  $responseType
     * @param  array<string,string>  $headers
     * @return CrmResponse<T>
     */
    public function requestBookingOriginalJson(string $method, string $path, string $operationId, string $responseType,
        string $originalWire, string $originalKey, array $headers = []): CrmResponse
    {
        $methods = [
            'createBookingService' => 'POST', 'saveBookingService' => 'PUT',
            'recoverBookingServiceReceipt' => 'POST', 'booking.create_public' => 'POST',
            'booking.reschedule_public' => 'POST', 'booking.cancel_public' => 'POST', 'booking.public_receipt' => 'POST',
        ];
        if (($methods[$operationId] ?? null) !== $method) {
            throw new \InvalidArgumentException('The original JSON protocol requires a real native Booking operation and method.');
        }
        Bookings\WireCodec::key($originalKey, str_starts_with($operationId, 'booking.'));
        $hasEffect = ! in_array($operationId, ['recoverBookingServiceReceipt', 'booking.public_receipt'], true);
        Bookings\WireCodec::object($originalWire, $hasEffect ? 65536 : 393216);
        $response = $this->execute($method, $path, $operationId, $responseType, [], $originalWire,
            $originalKey, $hasEffect, $headers, true, false);
        if (! $response instanceof CrmResponse) {
            throw new \LogicException('An original Booking JSON response is required.');
        }

        return $response;
    }

    /** Native Task time POST writes and query-only preview/original recovery.
     * @template T of WireModel
     * @param class-string<T> $responseType
     * @param array<string,string> $headers
     * @return CrmResponse<T> */
    public function requestTaskTimeBillingJson(string $path, string $operationId, string $responseType,
        string $wire, ?string $originalKey = null, array $headers = []): CrmResponse
    {
        $cases = ['linkServiceTicketTask' => 'link', 'unlinkServiceTicketTask' => 'unlink',
            'previewServiceTimeInvoice' => 'preview', 'getOriginalServiceTicketTaskLinkReceipt' => 'originalLink',
            'getOriginalServiceTicketTaskUnlinkReceipt' => 'originalUnlink'];
        $case = $cases[$operationId] ?? throw new \InvalidArgumentException('Supply a real native Task time POST operation.');
        $hasEffect = in_array($case, ['link', 'unlink'], true);
        if ($hasEffect) {
            if ($originalKey === null) {
                throw new \InvalidArgumentException('Supply the unchanged original Task time key.');
            }
            TaskTimeBilling\WireCodec::key($originalKey);
            TaskTimeBilling\WireCodec::intent($case, $wire);
        } else {
            if ($originalKey !== null) {
                throw new \InvalidArgumentException('Native Task time preview/recovery has no new idempotency key.');
            }
            if ($case === 'preview') {
                TaskTimeBilling\WireCodec::preview($wire);
            } else {
                TaskTimeBilling\WireCodec::recovery($case === 'originalLink' ? 'link' : 'unlink', $wire);
            }
        }
        $response = $this->execute('POST', $path, $operationId, $responseType, [], $wire, $originalKey,
            $hasEffect, $headers, true, false, ! $hasEffect, 200);
        if (! $response instanceof CrmResponse) {
            throw new \LogicException('A native Task time JSON response is required.');
        }

        return $response;
    }

    /** Six real VerticalPacks POST operations; native read wrappers never receive an autogenerated key.
     * @template T of WireModel
     * @param class-string<T> $responseType
     * @param array<string,string> $headers
     * @return CrmResponse<T> */
    public function requestVerticalPackJson(string $path, string $operationId, string $responseType,
        string $wire, ?string $originalKey = null, array $headers = []): CrmResponse
    {
        $cases = ['crmConfigurationPreviewPack' => 'preview', 'crmConfigurationGetPackPlan' => 'plan',
            'crmConfigurationGetPackConflicts' => 'conflicts', 'crmConfigurationApplyPack' => 'apply',
            'crmConfigurationUndoPackInstallation' => 'undo', 'crmConfigurationGetOriginalPackReceipt' => 'recover'];
        $case = $cases[$operationId] ?? throw new \InvalidArgumentException('Supply one real native VerticalPacks POST operation.');
        $hasEffect = in_array($case, ['apply', 'undo'], true);
        if ($hasEffect) {
            if ($originalKey === null) {
                throw new \InvalidArgumentException('Keep the actual original VerticalPacks key.');
            }
            VerticalPacks\WireCodec::key($originalKey);
            match ($case) {
                'apply' => VerticalPacks\WireCodec::apply($wire), 'undo' => VerticalPacks\WireCodec::undo($wire)
            };
        } else {
            if ($originalKey !== null) {
                throw new \InvalidArgumentException('A native query-only wrapper has no new idempotency key.');
            }
            VerticalPacks\WireCodec::query($case, $wire);
        }
        $response = $this->execute('POST', $path, $operationId, $responseType, [], $wire, $originalKey,
            $hasEffect, $headers, true, false, !$hasEffect, 200);
        if (!$response instanceof CrmResponse) {
            throw new \LogicException('A native VerticalPacks JSON response is required.');
        }

        return $response;
    }

    /** @param array<string,string> $headers */
    public function requestControl(string $path, string $operationId, string $wire, array $headers = []): \Psr\Http\Message\ResponseInterface
    {
        if (! in_array($operationId, ['automation.crm_sequence_pause', 'automation.crm_sequence_resume', 'automation.crm_sequence_cancel'], true)) {
            throw new \InvalidArgumentException('The native void/no-key protocol is qualified only for Sequence controls.');
        }
        \Factuarea\Sdk\Custom\Crm\Sequence\WireCodec::decode($wire);
        $response = $this->execute('PATCH', $path, $operationId, WireModel::class, [], $wire, null,
            false, $headers, false, true);
        if ($response instanceof CrmResponse) {
            throw new \LogicException('A native void control response is required.');
        }

        return $response;
    }

    /** @template T of WireModel
     * @param class-string<T> $responseType
     * @param array<string,scalar|null> $query
     * @param array<string,string> $headers
     * @return CrmResponse<T>|\Psr\Http\Message\ResponseInterface */
    private function execute(string $method, string $path, string $operationId, string $responseType,
        array $query, ?string $payload, ?string $idempotencyKey, bool $hasEffect, array $headers,
        bool $originalJson, bool $voidControl, bool $queryPost = false, ?int $expectedStatus = null): CrmResponse|\Psr\Http\Message\ResponseInterface
    {
        $mutating = $method !== 'GET';
        if ($mutating && ! $voidControl && ! $originalJson
            && ($idempotencyKey === null || preg_match('/^[\x20-\x7e]{1,255}$/D', $idempotencyKey) !== 1)) {
            throw new \InvalidArgumentException('Supply the original printable ASCII Idempotency-Key (1–255 characters).');
        }
        $baseUrl = rtrim($this->configuration->getTemplatedServerUrl(), '/');
        $headers = ['Accept' => 'application/json', 'user-agent' => $this->configuration->userAgent] + $headers;
        if ($idempotencyKey !== null) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }
        if ($payload !== null) {
            $headers['Content-Type'] = 'application/json';
        }
        $context = new Hooks\HookContext($this->configuration, $baseUrl, $operationId, null, $this->configuration->securitySource);
        $request = new Request($method, $baseUrl.'/'.$path, $headers, $payload);
        $request = $this->configuration->hooks->beforeRequest(new Hooks\BeforeRequestContext($context), $request);
        if ($voidControl || $queryPost) {
            // A configured optional key hook cannot add a control's nonexistent key.
            $request = $request->withoutHeader('Idempotency-Key');
        }
        $options = Utils::convertHeadersToOptions($request, ['http_errors' => false, 'query' => $query]);
        if ($expectedStatus !== null) {
            // A Task time POST redirect cannot resend a write or an original-recovery query.
            $options['allow_redirects'] = false;
        }
        if ($voidControl || $queryPost) {
            $options['factuarea_no_auto_idempotency'] = true;
            // Keep hook/security headers on the PSR request while disabling default client headers.
            // Guzzle's documented headers:null avoids both a generated key and deprecated null header values.
            $options['headers'] = null;
        } else {
            $request = Utils::removeHeaders($request);
        }
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
                if ($mutating && $hasEffect && $idempotencyKey !== null) {
                    throw new UnconfirmedMutationException($operationId, $idempotencyKey, $failure);
                }
                throw $failure;
            }
        }
        if ($response->getStatusCode() >= 400) {
            $response = $this->configuration->hooks->afterError(new Hooks\AfterErrorContext($context), $response, null);
        }
        $status = $response->getStatusCode();
        if ($voidControl && $status >= 200 && $status < 300) {
            $response = $this->configuration->hooks->afterSuccess(new Hooks\AfterSuccessContext($context), $response);
            if ($response->getStatusCode() !== 204 || (string) $response->getBody() !== '') {
                throw new APIException('Unknown native control completion', $response->getStatusCode(), (string) $response->getBody(), $response);
            }

            return $response;
        }
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
            throw CrmApiException::fromErrorThrowable($error->toException(), write: $mutating && $hasEffect);
        }
        if ($status < 200 || $status >= 300) {
            throw new APIException('Unknown status code received', $status, (string) $response->getBody(), $response);
        }
        $response = $this->configuration->hooks->afterSuccess(new Hooks\AfterSuccessContext($context), $response);
        if ($expectedStatus !== null && $response->getStatusCode() !== $expectedStatus) {
            throw new APIException('Unknown native Task time completion', $response->getStatusCode(), (string) $response->getBody(), $response);
        }
        $values = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \UnexpectedValueException('The CRM response must be an object.');
        }

        $responseFields = [];
        foreach (get_object_vars($values) as $name => $value) {
            if (! is_string($name)) {
                throw new \UnexpectedValueException('A named CRM response member is required.');
            }
            $responseFields[$name] = $value;
        }

        return new CrmResponse($responseType::fromArray($responseFields), $response, $idempotencyKey);
    }
}
