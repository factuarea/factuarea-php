<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\Schema;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\SDKConfiguration;

/** Complete existing native Booking slice; current guards and publication proofs remain server-owned. */
final readonly class BookingClient
{
    public function __construct(private Transport $transport, private SDKConfiguration $configuration, private RequiredPaths $paths)
    {
    }

    /** @return CrmResponse<ServicePage> */
    public function listBookingServices(?string $cursor = null, int $limit = 25): CrmResponse
    {
        $this->surface('administrative');

        return $this->transport->request('GET', $this->paths->path('list_services'), 'listBookingServices', ServicePage::class,
            self::page($cursor, $limit), null, null, false);
    }

    /** @param array<string,string> $filters Native public names; no hidden/contact filters.
     * @return CrmResponse<ReservationPage> */
    public function listBookings(array $filters = [], ?string $cursor = null, int $limit = 25): CrmResponse
    {
        $this->surface('administrative');
        Schema::validate(['type' => 'object', 'additionalProperties' => false, 'properties' => ['service_id' => Schemas::ID,
            'host_id' => Schemas::HOST, 'status' => Schemas::STATUS, 'from' => Schemas::INSTANT, 'through' => Schemas::INSTANT]], (object) $filters);
        foreach (['from', 'through'] as $field) {
            if (isset($filters[$field])) {
                WireCodec::instant($filters[$field]);
            }
        }
        if (isset($filters['from'], $filters['through'])) {
            WireCodec::interval($filters['from'], $filters['through']);
        }

        return $this->transport->request('GET', $this->paths->path('list_bookings'), 'listBookings', ReservationPage::class,
            $filters + self::page($cursor, $limit), null, null, false);
    }

    /** Current editable head; never substituted for an original receipt.
     * @return CrmResponse<ServiceResult> */
    public function getBookingService(string $serviceId): CrmResponse
    {
        $this->surface('administrative');
        $response = $this->transport->request('GET', $this->paths->path('get_service', $serviceId), 'getBookingService', ServiceResult::class, [], null, null, false);
        if ($response->body->data->id !== $serviceId) {
            throw new \UnexpectedValueException('The native service head must match its route identity.');
        }

        return $response;
    }

    /** Prepared native create: sends no caller-generated service UUID or approval boolean.
     * @return CrmResponse<ServiceResult> */
    public function createBookingService(OriginalIntent $intent): CrmResponse
    {
        if ($intent->serviceId !== null) {
            throw new \InvalidArgumentException('Use the native original create intent without a candidate service ID.');
        }

        return $this->saveService($intent);
    }

    /** @return CrmResponse<ServiceResult> */
    public function saveBookingService(OriginalIntent $intent): CrmResponse
    {
        if ($intent->serviceId === null) {
            throw new \InvalidArgumentException('Use the original route UUID and positive CAS for an update.');
        }

        return $this->saveService($intent);
    }

    /** Native POST Query with exact original wire/key; null means original absence, never a new head.
     * @phpstan-impure
     * @return CrmResponse<ServiceReceiptResult> */
    public function recoverBookingServiceReceipt(OriginalIntent $intent): CrmResponse
    {
        $this->administrativeIntent($intent);
        $body = new RecoveryBody($intent->originalWire);
        $response = $this->transport->requestBookingOriginalJson('POST', $this->paths->path($intent->serviceId === null ? 'recover_create' : 'recover_save', $intent->serviceId),
            'recoverBookingServiceReceipt', ServiceReceiptResult::class, $body->toJson(), $intent->originalKey);
        if ($response->body->data !== null) {
            self::assertServiceReceipt($intent, $response->body->data);
        }

        return $response;
    }

    /** @return CrmResponse<PublicConfiguration> */
    public function getPublicBookingConfiguration(string $publicId): CrmResponse
    {
        $this->surface('public_booking');
        $response = $this->transport->request('GET', $this->paths->path('configuration', $publicId), 'booking.public_configuration', PublicConfiguration::class, [], null, null, false);
        if ($response->body->data->public_id !== $publicId) {
            throw new \UnexpectedValueException('Public configuration must match its native route.');
        }

        return $response;
    }

    /** @return CrmResponse<PublicAvailability> */
    public function getBookingAvailability(string $publicId, int $publicationVersion, string $from, string $to, string $visitorTimezone): CrmResponse
    {
        $this->surface('public_booking');
        WireCodec::version($publicationVersion);
        WireCodec::interval($from, $to);
        if (! in_array($visitorTimezone, \DateTimeZone::listIdentifiers(\DateTimeZone::ALL_WITH_BC), true)) {
            throw new \InvalidArgumentException('Supply the explicit native visitor timezone.');
        }
        $response = $this->transport->request('GET', $this->paths->path('availability', $publicId), 'booking.get_public_availability', PublicAvailability::class,
            ['publication_version' => $publicationVersion, 'from' => $from, 'to' => $to, 'visitor_timezone' => $visitorTimezone], null, null, false);
        $data = $response->body->data;
        if ($data->public_id !== $publicId || $data->publication_version !== $publicationVersion || $data->visitor_timezone !== $visitorTimezone) {
            throw new \UnexpectedValueException('Availability must retain the current requested publication and timezone.');
        }
        foreach ($data->slots as $slot) {
            WireCodec::interval($slot->start_at, $slot->end_at);
        }

        return $response;
    }

    /** Possession capability is HEADER-only; this GET is a current head, not an effect receipt.
     * @return CrmResponse<PublicManagement> */
    public function getPublicBookingManagement(string $publicId, #[\SensitiveParameter] string $manageToken): CrmResponse
    {
        $this->surface('public_booking');
        WireCodec::managementToken($manageToken);
        $response = $this->transport->request('GET', $this->paths->path('management', $publicId), 'booking.get_public_management', PublicManagement::class,
            [], null, null, false, ['Booking-Management-Token' => $manageToken]);
        WireCodec::interval($response->body->data->start_at, $response->body->data->end_at);

        return $response;
    }

    /** @return CrmResponse<PublicResult> */
    public function createPublicBooking(OriginalIntent $intent): CrmResponse
    {
        return $this->writePublic('booking.create_public', 'create', $intent);
    }

    /** @return CrmResponse<PublicResult> */
    public function reschedulePublicBooking(OriginalIntent $intent): CrmResponse
    {
        return $this->writePublic('booking.reschedule_public', 'reschedule', $intent);
    }

    /** Native cancellation has an ORIGINAL receipt. It is not a Sequence void/no-key control.
     * @return CrmResponse<PublicResult> */
    public function cancelPublicBooking(OriginalIntent $intent): CrmResponse
    {
        return $this->writePublic('booking.cancel_public', 'cancel', $intent);
    }

    /** Readonly POST Query; never resends create/reschedule/cancel or mints IDs.
     * @return CrmResponse<PublicResult> */
    public function recoverPublicBookingReceipt(OriginalIntent $intent): CrmResponse
    {
        $this->publicIntent($intent);
        $body = new RecoveryBody($intent->originalWire, $intent->operation);
        $response = $this->transport->requestBookingOriginalJson('POST', $this->paths->path('receipt', $intent->publicId),
            'booking.public_receipt', PublicResult::class, $body->toJson(), $intent->originalKey,
            ['X-Booking-Publication-Version' => (string) $intent->publicationVersion]);
        self::assertPublicReceipt($intent, $response->body->data);

        return $response;
    }

    /** @return CrmResponse<ServiceResult> */
    private function saveService(OriginalIntent $intent): CrmResponse
    {
        $this->administrativeIntent($intent);
        $create = $intent->serviceId === null;
        try {
            $response = $this->transport->requestBookingOriginalJson($create ? 'POST' : 'PUT', $this->paths->path($create ? 'create_service' : 'save_service', $intent->serviceId),
                $create ? 'createBookingService' : 'saveBookingService', ServiceResult::class, $intent->originalWire, $intent->originalKey);
            if ($response->statusCode() !== ($create ? 201 : 200)) {
                throw new \UnexpectedValueException('The native service ACK must retain its actual creation/update status.');
            }
            self::assertServiceReceipt($intent, $response->body->data);

            return $response;
        } catch (\Throwable $failure) {
            self::rethrow($intent, $failure);
        }
    }

    /** @return CrmResponse<PublicResult> */
    private function writePublic(string $operation, string $kind, OriginalIntent $intent): CrmResponse
    {
        $this->publicIntent($intent);
        if ($intent->operation !== $operation) {
            throw new \InvalidArgumentException('Keep the original native public operation.');
        }
        try {
            $response = $this->transport->requestBookingOriginalJson('POST', $this->paths->path($kind, $kind === 'create' ? $intent->publicId : null),
                $operation, PublicResult::class, $intent->originalWire, $intent->originalKey,
                $kind === 'create' ? ['X-Booking-Publication-Version' => (string) $intent->publicationVersion] : []);
            if ($response->statusCode() !== ($kind === 'create' ? 201 : 200)) {
                throw new \UnexpectedValueException('The native public ACK must retain its actual status.');
            }
            self::assertPublicReceipt($intent, $response->body->data);

            return $response;
        } catch (\Throwable $failure) {
            self::rethrow($intent, $failure);
        }
    }

    private static function assertServiceReceipt(OriginalIntent $intent, Service $service): void
    {
        if ($intent->serviceId !== null && ($service->id !== $intent->serviceId || $service->version !== $intent->expectedVersion + 1)) {
            throw new \UnexpectedValueException('The original service receipt must match its target and original CAS.');
        }
    }

    private static function assertPublicReceipt(OriginalIntent $intent, PublicReceipt $receipt): void
    {
        $cancel = $intent->operation === 'booking.cancel_public';
        if (($intent->bookingId !== null && ($receipt->id !== $intent->bookingId || $receipt->version !== $intent->expectedVersion + 1))
            || ($intent->startAt !== null && ($receipt->startAt !== $intent->startAt || $receipt->endAt !== $intent->endAt))
            || $receipt->status !== ($cancel ? 'cancelled' : 'confirmed')
            || ($cancel ? $receipt->manageToken !== Omitted::Value : ! is_string($receipt->manageToken))) {
            throw new \UnexpectedValueException('The original public Booking receipt must retain its causal identity, CAS, slot and token projection.');
        }
    }

    private function administrativeIntent(OriginalIntent $intent): void
    {
        $this->surface('administrative');
        if ($intent->operation !== 'booking.save_service') {
            throw new \InvalidArgumentException('Use the native administrative original intent.');
        }
    }

    private function publicIntent(OriginalIntent $intent): void
    {
        $this->surface('public_booking');
        if (! in_array($intent->operation, ['booking.create_public', 'booking.reschedule_public', 'booking.cancel_public'], true)
            || $intent->publicId === null || $intent->publicationVersion === null) {
            throw new \InvalidArgumentException('Use the original native public Booking intent.');
        }
    }

    private function surface(string $surface): void
    {
        if ($this->paths->surface !== $surface) {
            throw new \InvalidArgumentException('Use the explicitly supplied native Booking surface.');
        }
        if ($surface === 'public_booking') {
            if ($this->configuration->serverUrl === '' || $this->configuration->hasSecurity()) {
                throw new \InvalidArgumentException('Public Booking requires an explicit native base URL and its separate unauthenticated SDK configuration.');
            }
            $client = $this->configuration->client ?? throw new \LogicException('The canonical configured SDK client is required.');
            $headers = $client->getConfig('headers') ?? [];
            if (! is_array($headers) || $client->getConfig('auth') !== null) {
                throw new \InvalidArgumentException('Public Booking does not inherit staff credentials.');
            }
            foreach (array_keys($headers) as $header) {
                if (! is_string($header) || in_array(strtolower($header), ['authorization', 'idempotency-key', 'booking-management-token', 'x-booking-publication-version'], true)) {
                    throw new \InvalidArgumentException('Public Booking secrets/versions must belong to each explicit native intent.');
                }
            }
        }
    }

    /** @return array<string,string|int> */
    private static function page(?string $cursor, int $limit): array
    {
        if ($limit < 1 || $limit > 100 || ($cursor !== null && ($cursor === '' || strlen($cursor) > 4096))) {
            throw new \InvalidArgumentException('Use the native page limit and opaque cursor.');
        }

        return $cursor === null ? ['limit' => $limit] : ['limit' => $limit, 'cursor' => $cursor];
    }

    private static function rethrow(OriginalIntent $intent, \Throwable $failure): never
    {
        if ($failure instanceof CrmApiException) {
            $status = $failure->statusCode;
            if (! $failure->unconfirmed && $status !== null && $status >= 400 && $status < 500) {
                throw $failure;
            }
        }

        if ($failure instanceof ErrorThrowable || $failure instanceof APIException) {
            $status = $failure instanceof ErrorThrowable ? ($failure->container->rawResponse?->getStatusCode() ?? $failure->getCode()) : $failure->statusCode;
            if ($status >= 400 && $status < 500) {
                throw $failure;
            }
        }
        throw new UnconfirmedWriteException($intent, $failure);
    }
}
