<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\Opportunities\FilterPredicate;
use Factuarea\Sdk\Custom\Crm\Opportunities\Filters;
use Factuarea\Sdk\Custom\Crm\Opportunities\HistoryEntry;
use Factuarea\Sdk\Custom\Crm\Opportunities\ListOpportunitiesRequest;
use Factuarea\Sdk\Custom\Crm\Opportunities\MissingOpportunity;
use Factuarea\Sdk\Custom\Crm\Opportunities\Opportunity;
use Factuarea\Sdk\Custom\Crm\Opportunities\OpportunityPageResult;
use Factuarea\Sdk\Custom\Crm\Opportunities\OpportunityResult;
use Factuarea\Sdk\Custom\Crm\Opportunities\Participant;
use Factuarea\Sdk\Custom\Crm\Opportunities\PlaybookAnswer;
use Factuarea\Sdk\Custom\Crm\Opportunities\TypedFilters;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CodecTest extends TestCase
{
    public function test_native_detail_preserves_exact_money_dates_public_id_aliases_and_nested_typed_snapshots(): void
    {
        $opportunity = OpportunityMother::opportunity();
        $decoded = OpportunityResult::fromArray(['data' => $opportunity->jsonSerialize()])->data;
        self::assertInstanceOf(Opportunity::class, $decoded);
        self::assertSame('9999999999999999.9999', $decoded->amount);
        self::assertSame('10.50', $decoded->probability);
        self::assertSame('2028-02-29', $decoded->expectedClose);
        self::assertSame(OpportunityMother::USER, $decoded->ownerId);
        self::assertNotNull($decoded->participants);
        self::assertNotEmpty($decoded->participants);
        self::assertNotNull($decoded->playbookAnswers);
        self::assertNotEmpty($decoded->playbookAnswers);
        self::assertNotNull($decoded->history);
        self::assertNotEmpty($decoded->history);
        self::assertInstanceOf(Participant::class, $decoded->participants[0]);
        self::assertInstanceOf(PlaybookAnswer::class, $decoded->playbookAnswers[0]);
        self::assertInstanceOf(HistoryEntry::class, $decoded->history[0]);
        self::assertFalse($decoded->playbookAnswers[0]->answer);
        self::assertSame([], $decoded->playbookAnswers[0]->evidenceIds);
        self::assertSame('0.00', $decoded->participants[0]->creditPercentage);
        self::assertSame($opportunity->toJson(), $decoded->toJson());
        self::assertArrayNotHasKey('uuid', $decoded->toArray());
    }

    public function test_current_null_masks_are_preserved_and_cannot_be_replaced_by_missing_fields_or_empty_defaults(): void
    {
        $values = OpportunityMother::values();
        foreach (['amount', 'currency', 'owner_id', 'participants', 'playbook_answers', 'history'] as $field) {
            $values[$field] = null;
        }
        $dto = Opportunity::fromArray($values);
        self::assertNull($dto->amount);
        self::assertNull($dto->participants);
        self::assertNull($dto->playbookAnswers);
        self::assertNull($dto->history);
        unset($values['amount']);
        $this->expectException(\InvalidArgumentException::class);
        Opportunity::fromArray($values);
    }

    public function test_native_missing_detail_variant_is_distinct_from_data_null_and_a_partially_hydrated_opportunity(): void
    {
        $missing = OpportunityResult::fromArray(['data' => (object) ['opportunity' => null]]);
        self::assertInstanceOf(MissingOpportunity::class, $missing->data);
        self::assertSame('{"data":{"opportunity":null}}', $missing->toJson());
        $this->expectException(\InvalidArgumentException::class);
        OpportunityResult::fromArray(['data' => null]);
    }

    /** @return iterable<string,array{string,mixed}> */
    public static function invalidFields(): iterable
    {
        yield 'float money' => ['amount', 1.25];
        yield 'money scale' => ['weighted_value', '1.25'];
        yield 'money overflow' => ['amount', '10000000000000000.0000'];
        yield 'probability over 100' => ['probability', '100.01'];
        yield 'invalid calendar date' => ['expected_close', '2027-02-29'];
        yield 'invalid timestamp' => ['stage_changed_at', '2026-02-30T12:30:00Z'];
        yield 'numeric business ID' => ['lead_id', 32];
        yield 'historical User is not business' => ['lead_id', OpportunityMother::USER];
        yield 'private authority' => ['company_id', 42];
        yield 'SPA alias' => ['uuid', OpportunityMother::FIRST];
        yield 'blank title' => ['title', '   '];
    }

    #[DataProvider('invalidFields')]
    public function test_invalid_native_values_are_rejected_without_numeric_or_identity_coercion(string $field, mixed $value): void
    {
        $values = OpportunityMother::values();
        $values[$field] = $value;
        $this->expectException(\InvalidArgumentException::class);
        Opportunity::fromArray($values);
    }

    public function test_native_page_counts_and_large_aggregate_money_remain_separate_from_page_length_and_single_record_limit(): void
    {
        $wire = json_decode((string) OpportunityMother::page([OpportunityMother::FIRST], 'opaque.next', 100)->getBody(), false, 512, JSON_THROW_ON_ERROR);
        if (! $wire instanceof \stdClass || ! $wire->data instanceof \stdClass) {
            throw new \LogicException('Native page fixture must decode into an object.');
        }
        $page = OpportunityPageResult::fromArray(['data' => $wire->data])->data;
        self::assertSame(100, $page->authorizedCount);
        self::assertSame(1, $page->limit);
        self::assertCount(1, $page->items);
        self::assertNotNull($page->totalsByCurrency);
        self::assertNotEmpty($page->totalsByCurrency);
        self::assertSame('999999999999999999999999999999999999.9999', $page->totalsByCurrency[0]->amount);
        $wire->data->lost_count = 1;
        $this->expectException(\InvalidArgumentException::class);
        OpportunityPageResult::fromArray(['data' => $wire->data]);
    }

    public function test_snapshot_history_versions_and_participant_identity_are_checked_without_overwriting_original_values(): void
    {
        $values = OpportunityMother::values();
        $history = $values['history'];
        self::assertIsArray($history);
        self::assertInstanceOf(\stdClass::class, $history[0] ?? null);
        $history[0]->version = 8;
        self::reject(static fn () => Opportunity::fromArray($values));
        $values = OpportunityMother::values();
        $entries = $values['participants'];
        self::assertIsArray($entries);
        self::assertNotEmpty($entries);
        $entries[] = $entries[array_key_first($entries)];
        $values['participants'] = $entries;
        self::reject(static fn () => Opportunity::fromArray($values));
        $values = OpportunityMother::values();
        $entries = $values['playbook_answers'];
        self::assertIsArray($entries);
        self::assertNotEmpty($entries);
        $entries[] = $entries[array_key_first($entries)];
        $values['playbook_answers'] = $entries;
        self::reject(static fn () => Opportunity::fromArray($values));
    }

    public function test_typed_filters_preserve_omission_null_false_and_definition_versions_without_claiming_current_access(): void
    {
        $omitted = new FilterPredicate(OpportunityMother::DEFINITION, 3, 'is_unset');
        $null = new FilterPredicate(OpportunityMother::DEFINITION, 3, 'is_unset', null);
        $false = new FilterPredicate(OpportunityMother::DEFINITION, 3, 'eq', false);
        self::assertSame(Omitted::Value, $omitted->value);
        self::assertArrayNotHasKey('value', $omitted->toArray());
        self::assertArrayHasKey('value', $null->toArray());
        self::assertNull($null->value);
        $filters = new Filters(ownerId: OpportunityMother::USER, expectedCloseFrom: '2028-02-29', typedFilters: new TypedFilters(7, [$false]), sort: 'owner_id', direction: 'desc');
        $copy = Filters::fromArray($filters->toArray());
        self::assertSame($filters->toJson(), $copy->toJson());
        self::assertInstanceOf(TypedFilters::class, $copy->typedFilters);
        self::assertNotEmpty($copy->typedFilters->filters);
        self::assertFalse($copy->typedFilters->filters[0]->value);
        self::assertSame(7, $copy->typedFilters->definitionVersion);
        self::assertArrayNotHasKey('can_read', $copy->toArray());
        self::assertSame([], (new ListOpportunitiesRequest())->query());
        self::assertSame('{}', (new Filters())->toJson());
        self::reject(static fn () => new Filters(createdFrom: '2026-10-11', createdTo: '2026-10-10'));
        self::reject(static fn () => Filters::fromArray(['company_id' => 42]));
        self::reject(static fn () => Filters::fromArray(['owner_uuid' => OpportunityMother::USER]));
        self::reject(static fn () => new ListOpportunitiesRequest(cursor: ' ', limit: 1));
        self::reject(static fn () => new ListOpportunitiesRequest(cursor: str_repeat('a', 8193)));
        self::reject(static fn () => new ListOpportunitiesRequest(limit: 0));
    }

    private static function reject(callable $action): void
    {
        try {
            $action();
            self::fail('The native boundary should reject this invalid input.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
    }
}
