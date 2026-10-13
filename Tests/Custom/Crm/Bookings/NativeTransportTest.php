<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Bookings\BookingClient;
use Factuarea\Sdk\Custom\Crm\Bookings\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\Bookings\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\Bookings\UnconfirmedWriteException;
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\CrmUnconfirmedException;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class NativeTransportTest extends TestCase
{
    private const SERVICE = '019a4afd-5000-7000-8000-000000000001';
    private const BOOKING = '019a4afd-5000-7000-8000-000000000002';
    private const EFFECT = '019a4afd-5000-7000-8000-000000000003';
    private const PUBLIC_ID = 'native_public_booking_id_00000000';
    private const FROM = '2026-10-12T10:00:00Z';
    private const THROUGH = '2026-10-12T10:30:00Z';

    /** @var \ArrayObject<int,array{request:RequestInterface,response:?ResponseInterface,error:?\Throwable,options:array<string,mixed>}> */
    private \ArrayObject $history;
    private MockHandler $mock;
    private CrmClient $crm;

    private function paths(bool $public): RequiredPaths
    {
        return $public ? RequiredPaths::publicBooking('public/crm/booking/{publicId}/configuration', 'public/crm/booking/{publicId}/availability',
            'public/crm/booking/{publicId}/bookings', 'public/crm/booking/manage/reschedule', 'public/crm/booking/manage/cancel',
            'public/crm/booking/{publicId}/receipts', 'public/crm/booking/{publicId}/management')
            : RequiredPaths::administrative('crm/booking/services', 'crm/booking/bookings', 'crm/booking/services/{service}',
                'crm/booking/services', 'crm/booking/services/{service}', 'crm/booking/services/receipts', 'crm/booking/services/receipts/{service}');
    }

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses, bool $public = false, bool $staff = false): BookingClient
    {
        $this->history = new \ArrayObject();
        $this->mock = new MockHandler($responses);
        $stack = HandlerStack::create($this->mock);
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $builder = Factuarea::builder()->setServerUrl($public ? 'https://native-booking.fixture.invalid' : 'https://native-booking.fixture.invalid/v1')
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack, 'headers' => $public ? [] : ['Idempotency-Key' => 'unrelated-client-default']])))
            ->setRetryConfig(new RetryConfigBackoff(1, 1, 1.0, 10, true));
        if (! $public || $staff) {
            $builder->setSecurity(new Security(bearerAuth: 'fact_admin_booking_fixture'));
        }
        $sdk = $builder->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());
        $this->crm = new CrmClient($sdk);

        return $this->crm->bookings($this->paths($public));
    }

    private function request(int $index): RequestInterface
    {
        $record = $this->history[$index];
        self::assertNotNull($record, 'The configured native SDK client must have received this request.');

        return $record['request'];
    }

    /** @param array<string,mixed>|null $data */
    private function response(?array $data, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Cache-Control' => 'no-store'], json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }

    private function error(int $status, string $code): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '3'], json_encode(['error' => ['type' => $status === 409 ? 'conflict_error' : 'api_error',
            'code' => $code, 'message' => 'Native Booking error', 'param' => 'expected_version', 'request_id' => 'req_native_booking_fixture']], JSON_THROW_ON_ERROR));
    }

    /** @return array<string,mixed> */
    private function head(int $version): array
    {
        return ['id' => self::SERVICE, 'version' => $version];
    }

    /** @return array<string,mixed> */
    private function receipt(int $version, bool $cancel = false): array
    {
        $data = ['id' => self::BOOKING, 'version' => $version, 'status' => $cancel ? 'cancelled' : 'confirmed',
            'start_at' => self::FROM, 'end_at' => self::THROUGH, 'effect_id' => self::EFFECT];
        if (! $cancel) {
            $data['manage_token'] = 'bk1_'.str_repeat('b', 64);
        }

        return $data;
    }

    private function createWire(): string
    {
        return '{ "slot":{"start_at":"'.self::FROM.'","end_at":"'.self::THROUGH.'"},"visitor_fields":{"name":"Native fixture","email":"visitor@example.test"},"notice_version":"notice-v4","captcha_token":"native-captcha-fixture" }';
    }

    private function administrativeCreateWire(): string
    {
        return '{ "definition" : {"public_id":"'.self::PUBLIC_ID.'","state":"draft","host_id":"019a4afd-5000-4000-8000-000000000009","resource_id":"'.self::BOOKING.'",'
            .'"schedule":{"timezone":"Europe/Madrid","dst_policy":"reject_ambiguous","windows":[{"weekday":1,"start_minute":600,"end_minute":1020}],"exceptions":[],"duration_minutes":30,"buffer_before":0,"buffer_after":0,"capacity":1,"minimum_notice_minutes":60,"booking_horizon_days":30,"slot_step_minutes":15},'
            .'"required_sources":["booking"],"notice_version":"notice-v4","hold_minutes":15,"location":null}, "expected_version":null }';
    }

    private function cancelIntent(): OriginalIntent
    {
        return OriginalIntent::cancelPublic('{ "manage_token" : "bk1_'.str_repeat('b', 64).'", "expected_version":3 }', str_repeat('c', 64), self::PUBLIC_ID, 4, self::BOOKING);
    }

    public function test_administrative_lists_and_current_head_preserve_masks_cursor_filters_and_auth(): void
    {
        $client = $this->client([$this->response(['items' => [$this->head(3)], 'total' => 1, 'next_cursor' => 'opaque/cursor==']),
            $this->response(['items' => [['id' => self::BOOKING, 'version' => 4, 'status' => 'confirmed']], 'total' => 1, 'next_cursor' => null]), $this->response($this->head(9))]);
        $services = $client->listBookingServices('original/cursor==', 7);
        self::assertSame('opaque/cursor==', $services->body->data->nextCursor);
        self::assertSame(Omitted::Value, $services->body->data->items[0]->location);
        self::assertSame('confirmed', $client->listBookings(['service_id' => self::SERVICE, 'from' => self::FROM, 'through' => self::THROUGH])->body->data->items[0]->status);
        self::assertSame(9, $client->getBookingService(self::SERVICE)->body->data->version);
        parse_str($this->request(0)->getUri()->getQuery(), $query);
        self::assertSame(['limit' => '7', 'cursor' => 'original/cursor=='], $query);
        self::assertSame('/v1/crm/booking/services/'.self::SERVICE, $this->request(2)->getUri()->getPath());
        foreach ($this->history as $record) {
            self::assertSame('GET', $record['request']->getMethod());
            self::assertSame('', (string) $record['request']->getBody());
            self::assertSame('Bearer fact_admin_booking_fixture', $record['request']->getHeaderLine('Authorization'));
        }
    }

    public function test_administrative_create_and_update_send_exact_original_wire_keys_and_server_ids(): void
    {
        $client = $this->client([$this->response($this->head(1), 201), $this->response($this->head(4))]);
        $create = OriginalIntent::administrative($this->administrativeCreateWire(), 'opaque original-key');
        self::assertSame(self::SERVICE, $client->createBookingService($create)->body->data->id);
        $edit = OriginalIntent::administrative('{ "definition":{"location":null},"expected_version":3 }', 'original-update-key', self::SERVICE);
        self::assertSame(4, $client->saveBookingService($edit)->body->data->version);
        foreach ([$create, $edit] as $index => $intent) {
            self::assertSame($index === 0 ? 'POST' : 'PUT', $this->request($index)->getMethod());
            self::assertSame($intent->originalWire, (string) $this->request($index)->getBody());
            self::assertSame($intent->originalKey, $this->request($index)->getHeaderLine('Idempotency-Key'));
            self::assertNotSame('', $this->request($index)->getHeaderLine('Factuarea-Version'));
        }
        self::assertCount(2, $this->history);
    }

    public function test_administrative_lost_ack_recovers_by_readonly_post_without_uuid_candidate_or_redispatch(): void
    {
        $failure = new ConnectException('Lost original ACK', new Request('POST', 'https://native-booking.fixture.invalid/v1/crm/booking/services'));
        $client = $this->client([$failure, $this->response(null), $this->response($this->head(1))]);
        $intent = OriginalIntent::administrative($this->administrativeCreateWire(), 'persisted-create-key');
        try {
            $client->createBookingService($intent);
            self::fail('The original ACK is missing.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
        }
        self::assertCount(1, $this->history);
        self::assertNull($client->recoverBookingServiceReceipt($intent)->body->data);
        // Recovery after a process restart reconstructs the SAME original bytes/key, not a new effect.
        $afterRestart = OriginalIntent::administrative($intent->originalWire, $intent->originalKey);
        self::assertSame($intent->originalWire, $afterRestart->originalWire);
        self::assertSame($intent->originalKey, $afterRestart->originalKey);
        self::assertSame(1, $client->recoverBookingServiceReceipt($afterRestart)->body->data?->version);
        foreach ([1, 2] as $index) {
            $request = $this->request($index);
            self::assertSame('POST', $request->getMethod());
            self::assertSame('/v1/crm/booking/services/receipts', $request->getUri()->getPath());
            self::assertSame('', $request->getUri()->getQuery());
            self::assertSame(['original_wire_body' => $intent->originalWire], json_decode((string) $request->getBody(), true, 32, JSON_THROW_ON_ERROR));
            self::assertSame($intent->originalKey, $request->getHeaderLine('Idempotency-Key'));
        }
    }

    public function test_update_conflict_keeps_native_error_and_recovery_retains_original_cas_after_a_newer_head(): void
    {
        $client = $this->client([$this->error(409, 'resource_conflict'), $this->response($this->head(9)), $this->response($this->head(4))]);
        $intent = OriginalIntent::administrative('{"definition":{},"expected_version":3}', 'same-original-update', self::SERVICE);
        try {
            $client->saveBookingService($intent);
            self::fail('Native CAS must reject the update.');
        } catch (CrmApiException $error) {
            self::assertSame('expected_version', $error->param);
            self::assertSame('req_native_booking_fixture', $error->requestId);
            self::assertSame(409, $error->statusCode);
        }
        self::assertSame(9, $client->getBookingService(self::SERVICE)->body->data->version);
        self::assertSame(4, $client->recoverBookingServiceReceipt($intent)->body->data?->version);
        self::assertSame('/v1/crm/booking/services/receipts/'.self::SERVICE, $this->request(2)->getUri()->getPath());
        self::assertSame($intent->originalKey, $this->request(2)->getHeaderLine('Idempotency-Key'));
    }

    public function test_readonly_service_recovery_retains_a_maximum_original_wire_inside_the_larger_native_envelope(): void
    {
        $client = $this->client([$this->response(null)]);
        $wire = '{"definition":{},"expected_version":3}';
        $wire .= str_repeat(' ', 65536 - strlen($wire));
        $intent = OriginalIntent::administrative($wire, 'maximum-original-wire', self::SERVICE);
        self::assertNull($client->recoverBookingServiceReceipt($intent)->body->data);
        $request = $this->request(0);
        self::assertSame('POST', $request->getMethod());
        self::assertSame('/v1/crm/booking/services/receipts/'.self::SERVICE, $request->getUri()->getPath());
        self::assertGreaterThan(65536, strlen((string) $request->getBody()));
        self::assertSame(['original_wire_body' => $wire], json_decode((string) $request->getBody(), true, 32, JSON_THROW_ON_ERROR));
        self::assertSame($intent->originalKey, $request->getHeaderLine('Idempotency-Key'));
        self::assertCount(1, $this->history);
    }

    public function test_public_configuration_availability_and_management_gets_use_only_their_native_inputs(): void
    {
        $config = ['public_id' => self::PUBLIC_ID, 'publication_version' => 4, 'business_timezone' => 'Europe/Madrid', 'is_sandbox' => true,
            'service_display' => 'Native appointment', 'duration_minutes' => 30, 'buffer_before' => 0, 'buffer_after' => 0,
            'notice_version' => 'notice-v4', 'notice_copy' => 'Native notice', 'captcha' => (object) ['provider' => 'none']];
        $availability = ['public_id' => self::PUBLIC_ID, 'publication_version' => 4, 'business_timezone' => 'Europe/Madrid', 'visitor_timezone' => 'Europe/Madrid',
            'slots' => [(object) ['start_at' => self::FROM, 'end_at' => self::THROUGH]], 'as_of' => self::FROM, 'notice_version' => 'notice-v4', 'is_sandbox' => true];
        $head = $this->receipt(9);
        unset($head['manage_token'], $head['effect_id']);
        $client = $this->client([$this->response($config), $this->response($availability), $this->response($head)], true);
        self::assertSame('Native appointment', $client->getPublicBookingConfiguration(self::PUBLIC_ID)->body->data->service_display);
        self::assertCount(1, $client->getBookingAvailability(self::PUBLIC_ID, 4, self::FROM, self::THROUGH, 'Europe/Madrid')->body->data->slots);
        $token = 'bk1_'.str_repeat('b', 64);
        self::assertSame(9, $client->getPublicBookingManagement(self::PUBLIC_ID, $token)->body->data->version);
        foreach ($this->history as $record) {
            self::assertSame('GET', $record['request']->getMethod());
            self::assertFalse($record['request']->hasHeader('Authorization'));
            self::assertFalse($record['request']->hasHeader('Idempotency-Key'));
            self::assertFalse($record['request']->hasHeader('X-Booking-Publication-Version'));
            self::assertSame('', (string) $record['request']->getBody());
            self::assertStringNotContainsString($token, (string) $record['request']->getUri());
        }
        self::assertSame($token, $this->request(2)->getHeaderLine('Booking-Management-Token'));
        self::assertSame('', $this->request(2)->getUri()->getQuery());
    }

    public function test_public_create_lost_ack_and_recovery_preserve_original_bytes_key_and_publication(): void
    {
        $failure = new ConnectException('Lost public ACK', new Request('POST', 'https://native-booking.fixture.invalid/public/crm/booking/'.self::PUBLIC_ID.'/bookings'));
        $client = $this->client([$failure, $this->response($this->receipt(2))], true);
        $intent = OriginalIntent::createPublic($this->createWire(), str_repeat('a', 64), self::PUBLIC_ID, 4);
        try {
            $client->createPublicBooking($intent);
            self::fail('The original public ACK is missing.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
        }
        self::assertCount(1, $this->history);
        $receipt = $client->recoverPublicBookingReceipt($intent);
        self::assertSame(self::BOOKING, $receipt->body->data->id);
        self::assertSame(self::EFFECT, $receipt->body->data->effectId);
        self::assertSame($intent->originalWire, (string) $this->request(0)->getBody());
        self::assertSame('/public/crm/booking/'.self::PUBLIC_ID.'/receipts', $this->request(1)->getUri()->getPath());
        self::assertSame(['original_wire_body' => $intent->originalWire, 'operation' => $intent->operation], json_decode((string) $this->request(1)->getBody(), true, 32, JSON_THROW_ON_ERROR));
        foreach ([0, 1] as $index) {
            self::assertSame('POST', $this->request($index)->getMethod());
            self::assertSame('4', $this->request($index)->getHeaderLine('X-Booking-Publication-Version'));
            self::assertSame($intent->originalKey, $this->request($index)->getHeaderLine('Idempotency-Key'));
            self::assertFalse($this->request($index)->hasHeader('Authorization'));
        }
    }

    public function test_public_reschedule_and_cancel_have_original_receipts_cas_and_distinct_token_projections(): void
    {
        $client = $this->client([$this->response($this->receipt(3)), $this->response($this->receipt(4, true))], true);
        $wire = '{"manage_token":"bk1_'.str_repeat('b', 64).'","expected_version":2,"new_slot":{"start_at":"'.self::FROM.'","end_at":"'.self::THROUGH.'"}}';
        $reschedule = OriginalIntent::reschedulePublic($wire, str_repeat('d', 64), self::PUBLIC_ID, 4, self::BOOKING);
        self::assertSame(3, $client->reschedulePublicBooking($reschedule)->body->data->version);
        $cancel = $this->cancelIntent();
        $result = $client->cancelPublicBooking($cancel);
        self::assertSame('cancelled', $result->body->data->status);
        self::assertSame(Omitted::Value, $result->body->data->manageToken);
        foreach ([$reschedule, $cancel] as $index => $intent) {
            self::assertSame('POST', $this->request($index)->getMethod());
            self::assertSame('/public/crm/booking/manage/'.($index === 0 ? 'reschedule' : 'cancel'), $this->request($index)->getUri()->getPath());
            self::assertSame($intent->originalWire, (string) $this->request($index)->getBody());
            self::assertSame($intent->originalKey, $this->request($index)->getHeaderLine('Idempotency-Key'));
            self::assertFalse($this->request($index)->hasHeader('X-Booking-Publication-Version'));
        }
    }

    public function test_cancel_lost_ack_uses_receipt_lookup_and_does_not_repeat_the_cancellation(): void
    {
        $failure = new ConnectException('Lost cancellation ACK', new Request('POST', 'https://native-booking.fixture.invalid/public/crm/booking/manage/cancel'));
        $client = $this->client([$failure, $this->response($this->receipt(4, true))], true);
        $intent = $this->cancelIntent();
        try {
            $client->cancelPublicBooking($intent);
            self::fail('The original cancellation ACK is missing.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
        }
        self::assertCount(1, $this->history);
        self::assertSame(4, $client->recoverPublicBookingReceipt($intent)->body->data->version);
        self::assertCount(2, $this->history);
        self::assertSame('/public/crm/booking/'.self::PUBLIC_ID.'/receipts', $this->request(1)->getUri()->getPath());
        self::assertSame($intent->originalKey, $this->request(1)->getHeaderLine('Idempotency-Key'));
    }

    public function test_current_denial_and_service_failure_preserve_original_intent_and_native_error_without_resend(): void
    {
        $client = $this->client([$this->error(403, 'crm_access_denied'), $this->error(503, 'service_unavailable')]);
        $intent = OriginalIntent::administrative('{"definition":{},"expected_version":3}', 'retained-denied-intent', self::SERVICE);
        try {
            $client->saveBookingService($intent);
            self::fail('Current admission must deny the native write.');
        } catch (CrmApiException $error) {
            self::assertSame(403, $error->statusCode);
            self::assertSame('req_native_booking_fixture', $error->requestId);
            self::assertFalse($error->unconfirmed);
        }
        try {
            $client->saveBookingService($intent);
            self::fail('The native service failure must leave the ACK unconfirmed.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
            $native = $error->getPrevious();
            self::assertInstanceOf(CrmUnconfirmedException::class, $native);
            self::assertSame(503, $native->statusCode);
            self::assertSame('req_native_booking_fixture', $native->requestId);
        }
        self::assertCount(2, $this->history);
        self::assertSame($intent->originalKey, $this->request(1)->getHeaderLine('Idempotency-Key'));
    }

    public function test_public_surface_refuses_staff_security_before_http(): void
    {
        $client = $this->client([], true, true);
        try {
            $client->getPublicBookingConfiguration(self::PUBLIC_ID);
            self::fail('A public widget must not inherit authenticated staff SDK security.');
        } catch (\InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
    }

    public function test_foreign_original_ack_cannot_confirm_or_replace_the_causal_booking(): void
    {
        $wrong = $this->receipt(4, true);
        $wrong['id'] = self::SERVICE;
        $client = $this->client([$this->response($wrong)], true);
        $intent = $this->cancelIntent();
        try {
            $client->cancelPublicBooking($intent);
            self::fail('A foreign original result is not a confirmation.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
            self::assertInstanceOf(\UnexpectedValueException::class, $error->getPrevious());
        }
        self::assertCount(1, $this->history);
    }
}
