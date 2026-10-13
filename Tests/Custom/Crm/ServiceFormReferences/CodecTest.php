<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\ServiceFormReferences;

use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\CalendarReference;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\PolicyReference;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\QueueReference;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\References;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\ReferencesResult;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\ResponsibleUserReference;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** Structural offline V1 fixtures convey no native Company/current C7 grants. */
final class CodecTest extends TestCase
{
    public const QUEUE = '019a4afd-7200-7000-8000-000000000001';
    public const POLICY = '019a4afd-7200-7000-8000-000000000002';
    public const CALENDAR = '019a4afd-7200-7000-8000-000000000003';
    public const USER4 = '019a4afd-7200-4000-8000-000000000004';
    public const USER7 = '019a4afd-7200-7000-8000-000000000005';
    public static function references(): References
    {
        return new References([new QueueReference(self::QUEUE, self::QUEUE)], [new PolicyReference(self::POLICY, self::POLICY)],
            [new CalendarReference(self::CALENDAR, 'Calendario nativo ñ', 8)],
            [new ResponsibleUserReference(self::USER4, 'Usuario histórico'), new ResponsibleUserReference(self::USER7, 'Usuario actual')], 'actual-current:C7');
    }
    public function test_closed_v1_decodes_distinct_reference_types_and_independent_calendar_version(): void
    {
        $decoded = ReferencesResult::fromArray(['data' => self::references()->jsonSerialize()]);
        self::assertInstanceOf(QueueReference::class, $decoded->data->queues[0]);
        self::assertInstanceOf(PolicyReference::class, $decoded->data->policies[0]);
        self::assertInstanceOf(CalendarReference::class, $decoded->data->calendars[0]);
        self::assertInstanceOf(ResponsibleUserReference::class, $decoded->data->responsibleUsers[0]);
        self::assertSame(self::USER4, $decoded->data->responsibleUsers[0]->id);
        self::assertSame(self::USER7, $decoded->data->responsibleUsers[1]->id);
        self::assertSame(8, $decoded->data->calendars[0]->version);
        self::assertNotSame($decoded->data->policies[0]->id, $decoded->data->calendars[0]->id);
        self::assertSame('actual-current:C7', $decoded->data->authorizationVersion);
        self::assertSame(self::references()->toJson(), $decoded->data->toJson());
    }
    public function test_empty_admitted_collections_are_explicit_arrays_without_default_grants_or_calendar_inference(): void
    {
        $actual = new References([], [], [], [], 'native-current-empty');
        self::assertSame(['queues' => [], 'policies' => [], 'calendars' => [], 'responsible_users' => [], 'authorization_version' => 'native-current-empty'], $actual->toArray());
        $independent = new References([], [new PolicyReference(self::POLICY, self::POLICY)], [], [], 'native-current-without-calendar');
        self::assertSame([], $independent->calendars);
        self::assertCount(1, $independent->policies);
        self::assertArrayNotHasKey('can_configure', $actual->toArray());
        self::assertArrayNotHasKey('enabled', $actual->toArray());
    }
    /** @return iterable<string,array{string}> */
    public static function requiredFields(): iterable
    {
        foreach (['queues', 'policies', 'calendars', 'responsible_users', 'authorization_version'] as $field) {
            yield $field => [$field];
        }
    }
    #[DataProvider('requiredFields')]
    public function test_missing_current_fields_are_not_coerced_into_empty_or_null(string $field): void
    {
        $values = self::references()->toArray();
        unset($values[$field]);
        self::reject(static fn () => References::fromArray($values));
        $values = self::references()->toArray();
        $values[$field] = null;
        self::reject(static fn () => References::fromArray($values));
    }
    /** @return iterable<string,array{string,mixed}> */
    public static function privateFields(): iterable
    {
        yield 'grant false is still foreign' => ['can_configure', false];
        yield 'Company proof' => ['company_id', 42];
        yield 'scopes' => ['granted_scopes', ['*']];
        yield 'whole profile' => ['profile', (object) ['enabled' => true]];
    }
    #[DataProvider('privateFields')]
    public function test_private_fields_and_fabricated_permission_defaults_are_rejected(string $field, mixed $value): void
    {
        $values = self::references()->toArray();
        $values[$field] = $value;
        self::reject(static fn () => References::fromArray($values));
    }
    public function test_business_ids_do_not_accept_historical_user_uuid_or_spa_numeric_dual_aliases(): void
    {
        self::assertSame(self::USER4, (new ResponsibleUserReference(self::USER4, 'Actual name'))->id);
        self::reject(static fn () => new QueueReference(self::USER4, 'Invalid business ID'));
        self::reject(static fn () => new PolicyReference(self::USER4, 'Invalid business ID'));
        self::reject(static fn () => new CalendarReference(self::USER4, 'Invalid business ID', 1));
        self::reject(static fn () => QueueReference::fromArray(['id' => self::QUEUE, 'uuid' => self::QUEUE, 'name' => 'Dual alias']));
        self::reject(static fn () => ResponsibleUserReference::fromArray(['id' => 3, 'name' => 'Numeric ID']));
        self::reject(static fn () => new ResponsibleUserReference(strtoupper(self::USER4), 'Noncanonical ID'));
    }
    /** @return iterable<string,array{mixed}> */
    public static function invalidVersions(): iterable
    {
        yield 'zero' => [0];
        yield 'string' => ['8'];
        yield 'null' => [null];
    }
    #[DataProvider('invalidVersions')]
    public function test_calendar_version_is_required_positive_native_integer_without_a_policy_binding(mixed $version): void
    {
        self::reject(static fn () => CalendarReference::fromArray(['id' => self::CALENDAR, 'name' => 'Actual calendar', 'version' => $version]));
        self::reject(static fn () => CalendarReference::fromArray(['id' => self::CALENDAR, 'name' => 'Actual calendar']));
        self::reject(static fn () => PolicyReference::fromArray(['id' => self::POLICY, 'name' => 'Actual policy', 'calendar_id' => self::CALENDAR]));
    }
    public function test_collections_are_bounded_unique_and_current_names_are_preserved_without_local_fallback(): void
    {
        $queues = [];
        for ($index = 1; $index <= 100; $index++) {
            $queues[] = new QueueReference(sprintf('019a4afd-7200-7000-8000-%012d', $index), 'Actual native '.$index);
        }
        self::assertCount(100, (new References($queues, [], [], [], 'C7'))->queues);
        $overflow = [...$queues, new QueueReference('019a4afd-7200-7000-8000-000000000101', 'Actual native 101')];
        self::reject(static fn () => new References($overflow, [], [], [], 'C7'));
        self::reject(static fn () => new References([$queues[0], new QueueReference($queues[0]->id, 'Different name same ID')], [], [], [], 'C7'));
        self::reject(static fn () => new QueueReference(self::QUEUE, ' '));
        self::reject(static fn () => new CalendarReference(self::CALENDAR, str_repeat('ñ', 151), 8));
        self::assertSame(str_repeat('ñ', 150), (new CalendarReference(self::CALENDAR, str_repeat('ñ', 150), 8))->name);
        self::reject(static fn () => new References([], [], [], [], ' '));
    }
    private static function reject(callable $operation): void
    {
        try {
            $operation();
            self::fail('The exact native V1 whitelist must reject this value.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
    }
}
