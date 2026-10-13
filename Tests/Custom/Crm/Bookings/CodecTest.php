<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Bookings\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\Bookings\PublicConfiguration;
use Factuarea\Sdk\Custom\Crm\Bookings\RecoveryBody;
use Factuarea\Sdk\Custom\Crm\Bookings\ServicePage;
use Factuarea\Sdk\Custom\Crm\Bookings\ServiceReceiptResult;
use Factuarea\Sdk\Custom\Crm\Bookings\ServiceResult;
use Factuarea\Sdk\Custom\Crm\Bookings\WireCodec;
use Factuarea\Sdk\Custom\Crm\Omitted;
use PHPUnit\Framework\TestCase;

final class CodecTest extends TestCase
{
    private const ID = '019a4afd-5000-7000-8000-000000000001';

    public function test_administrative_original_bytes_and_null_cas_are_not_rebuilt_or_given_ids(): void
    {
        $wire = '{ "definition" : {"location":null}, "expected_version":null }';
        $intent = OriginalIntent::administrative($wire, 'opaque original key');
        self::assertSame($wire, $intent->originalWire);
        self::assertSame('opaque original key', $intent->originalKey);
        self::assertNull($intent->serviceId);
        self::assertNull($intent->expectedVersion);
        $lookup = json_decode((new RecoveryBody($wire))->toJson(), false, 32, JSON_THROW_ON_ERROR);
        self::assertInstanceOf(\stdClass::class, $lookup);
        self::assertSame($wire, $lookup->original_wire_body);
        $update = OriginalIntent::administrative('{"definition":{"location":null},"expected_version":7}', 'persisted-edit', self::ID);
        self::assertSame(7, $update->expectedVersion);
        foreach (['{"definition":{},"expected_version":0}', '{"definition":{},"id":"'.self::ID.'"}',
            '{"definition":{"host_uuid":"'.self::ID.'"}}', '{"definition":{},"definition":{}}',
            '{"definition":{},"def\u0069nition":{}}', '{"definition":{},"confirmed":true}'] as $invalid) {
            self::reject(static fn () => OriginalIntent::administrative($invalid, 'same-key'));
        }
        self::reject(static fn () => OriginalIntent::administrative('{"definition":{}}', 'same-key', self::ID));
        foreach ([' original-key', 'original-key ', "original\tkey"] as $key) {
            self::reject(static fn () => OriginalIntent::administrative($wire, $key));
        }
    }

    public function test_masked_service_fields_and_original_absence_remain_distinct(): void
    {
        $masked = ServiceResult::fromArray(['data' => (object) ['id' => self::ID, 'version' => 3]]);
        self::assertSame(Omitted::Value, $masked->data->location);
        self::assertArrayNotHasKey('state', $masked->data->toArray());
        $cleared = ServiceResult::fromArray(['data' => (object) ['id' => self::ID, 'version' => 3, 'location' => null]]);
        self::assertNull($cleared->data->location);
        self::assertNull(ServiceReceiptResult::fromArray(['data' => null])->data);
        $page = ServicePage::fromArray(['data' => (object) ['items' => [(object) ['id' => self::ID, 'version' => 3,
            'source_statuses' => (object) ['booking' => (object) ['verified' => false, 'revision' => str_repeat('a', 64), 'reason' => 'unavailable_adapter']]]], 'total' => 1, 'next_cursor' => 'opaque/cursor==']]);
        self::assertSame('opaque/cursor==', $page->data->nextCursor);
        $statuses = $page->data->items[0]->sourceStatuses;
        self::assertInstanceOf(\stdClass::class, $statuses);
        $bookingSource = $statuses->booking;
        self::assertInstanceOf(\stdClass::class, $bookingSource);
        self::assertFalse($bookingSource->verified);
        self::reject(static fn () => ServiceResult::fromArray(['data' => (object) ['id' => self::ID, 'version' => 3, 'visitor_email' => 'private@example.test']]));
    }

    public function test_public_key_and_management_secret_are_separate_and_debug_output_is_redacted(): void
    {
        $token = 'bk1_'.str_repeat('b', 64);
        $wire = '{"manage_token":"'.$token.'","expected_version":3}';
        $intent = OriginalIntent::cancelPublic($wire, str_repeat('a', 64), str_repeat('p', 32), 4, self::ID);
        self::assertSame($wire, $intent->originalWire);
        self::assertStringNotContainsString($token, print_r($intent, true));
        self::assertStringNotContainsString(str_repeat('a', 64), print_r($intent, true));
        self::reject(static fn () => WireCodec::key('ordinary-admin-key', true));
        self::reject(static fn () => WireCodec::managementToken(self::ID));
        self::reject(static fn () => OriginalIntent::cancelPublic('{"manage_token":"'.$token.'","expected_version":0}', str_repeat('a', 64), str_repeat('p', 32), 4, self::ID));
        self::reject(static fn () => WireCodec::interval('2026-10-10T10:00:00+00:00', '2026-10-10T11:00:00Z'));
    }

    public function test_public_configuration_captcha_does_not_infer_authentication_or_private_policy(): void
    {
        $data = (object) ['public_id' => str_repeat('p', 32), 'publication_version' => 4, 'business_timezone' => 'Europe/Madrid',
            'is_sandbox' => true, 'service_display' => 'Native appointment', 'duration_minutes' => 30, 'buffer_before' => 0,
            'buffer_after' => 0, 'notice_version' => 'notice-v4', 'notice_copy' => 'Native notice', 'captcha' => (object) ['provider' => 'none']];
        $config = PublicConfiguration::fromArray(['data' => $data]);
        self::assertSame('none', $config->data->captcha->provider);
        self::assertFalse(property_exists($config->data->captcha, 'site_key'));
        $private = clone $data;
        $private->company_id = 9;
        self::reject(static fn () => PublicConfiguration::fromArray(['data' => $private]));
        $invalid = clone $data;
        $invalid->captcha = (object) ['provider' => 'none', 'site_key' => 'invented'];
        self::reject(static fn () => PublicConfiguration::fromArray(['data' => $invalid]));
    }

    private static function reject(callable $operation): void
    {
        try {
            $operation();
            self::fail('The closed native Booking boundary must reject this input.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
    }
}
