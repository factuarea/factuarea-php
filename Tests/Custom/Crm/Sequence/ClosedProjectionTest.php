<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\Sequence\ListResult;
use Factuarea\Sdk\Custom\Crm\Sequence\SaveResult;
use PHPUnit\Framework\TestCase;

final class ClosedProjectionTest extends TestCase
{
    public function test_original_projection_cannot_substitute_mutable_definition_or_leak_enrolment_subject(): void
    {
        $schedule = ['id' => '019a4afd-5000-7000-8000-000000000001', 'version' => 7,
            'authorization_version' => 'current-native-C7', 'sandbox' => true, 'state' => 'active',
            'owner_id' => '019a4afd-5000-4000-8000-000000000002', 'team_id' => null, 'fields' => (object) []];
        $original = SaveResult::fromArray(['data' => (object) $schedule]);
        self::assertSame(7, $original->data->version);
        self::assertSame([], get_object_vars($original->data->fields));
        self::assertFalse(property_exists($original->data->fields, 'definition'));
        $current = $schedule;
        $current['fields'] = (object) ['definition' => '{"native-authorized-current-definition":true}'];
        $list = ['items' => [(object) $current], 'enrolments' => [], 'resource' => 'enrolments', 'next_cursor' => null, 'visible_count' => 0];
        $read = ListResult::fromArray(['data' => (object) $list]);
        $items = $read->data->items;
        self::assertInstanceOf(\stdClass::class, $items[0]);
        $fields = $items[0]->fields;
        self::assertInstanceOf(\stdClass::class, $fields);
        self::assertSame($current['fields']->definition, $fields->definition);
        self::assertSame('enrolments', $read->data->resource);
        foreach ([['data' => (object) $current], ['data' => (object) ($schedule + ['uuid' => $schedule['id']])]] as $invalid) {
            try {
                SaveResult::fromArray($invalid);
                self::fail('The original result accepted mutable definition or internal UUID alias.');
            } catch (\InvalidArgumentException $exception) {
                self::assertNotEmpty($exception->getMessage());
            }
        }
        $metadata = ['id' => $schedule['id'], 'version' => 2, 'authorization_version' => 'C7', 'sandbox' => true,
            'sequence_id' => $schedule['id'], 'owner_id' => $schedule['owner_id'], 'team_id' => null,
            'generation' => 1, 'definition_version' => 7, 'state' => 'waiting', 'current_step' => 0,
            'next_due_at' => null, 'stop_reason' => null, 'created_at' => '2026-10-10T10:00:00+00:00', 'updated_at' => '2026-10-10T10:00:00+00:00',
            'subject_id' => $schedule['id']];
        $list['enrolments'] = [(object) $metadata];
        $this->expectException(\InvalidArgumentException::class);
        ListResult::fromArray(['data' => (object) $list]);
    }
}
