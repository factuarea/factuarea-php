<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\Opportunities\Opportunity;
use Factuarea\Sdk\Custom\Crm\Opportunities\OpportunityPageResult;
use GuzzleHttp\Psr7\Response;

/** Offline public V1 structural facts only; these fixtures are not native Company/permission proofs. */
final class OpportunityMother
{
    public const FIRST = '019a4afd-7300-7000-8000-000000000001';
    public const SECOND = '019a4afd-7300-7000-8000-000000000002';
    public const USER = '019a4afd-7300-4000-8000-000000000003';
    public const PERSON = '019a4afd-7300-7000-8000-000000000004';
    public const DEFINITION = '019a4afd-7300-7000-8000-000000000005';

    /** @return array<string,mixed> */
    public static function values(string $id = self::FIRST): array
    {
        return [
            'id' => $id, 'version' => 7, 'status' => 'open', 'title' => 'Opportunity ñ',
            'lead_id' => self::SECOND, 'contact_id' => null, 'pipeline_id' => null, 'stage_id' => null,
            'owner_id' => self::USER, 'team_id' => null, 'amount' => '9999999999999999.9999', 'currency' => 'EUR',
            'probability' => '10.50', 'probability_override' => null, 'override_reason' => null,
            'weighted_value' => '1049999999999999.9999', 'expected_close' => '2028-02-29', 'next_step' => null,
            'closed_at' => null, 'loss_reason_id' => null, 'close_reason' => null, 'stage_changed_at' => '2026-10-10T10:30:00+02:00',
            'participants' => [(object) ['person_id' => self::PERSON, 'role' => 'buyer', 'credit_percentage' => '0.00']],
            'playbook_answers' => [(object) ['definition_id' => self::DEFINITION, 'definition_version' => 2, 'key' => 'approved', 'answer' => false, 'evidence_ids' => []]],
            'history' => [(object) ['version' => 1, 'kind' => 'created', 'actor_id' => self::USER, 'occurred_at' => '2026-10-09T08:00:00Z',
                'pipeline_id' => null, 'stage_id' => null, 'pipeline_label' => null, 'stage_label' => null, 'amount' => '0.0000',
                'currency' => 'EUR', 'probability' => '0.00', 'outcome' => 'open', 'loss_reason_id' => null, 'reason' => null]],
        ];
    }

    public static function opportunity(string $id = self::FIRST): Opportunity
    {
        return Opportunity::fromArray(self::values($id));
    }

    /** @param list<string> $ids */
    public static function page(array $ids = [self::FIRST], ?string $cursor = null, int $total = 1): Response
    {
        $items = array_map(static fn (string $id): object => self::opportunity($id)->jsonSerialize(), $ids);
        $result = OpportunityPageResult::fromArray(['data' => (object) ['items' => $items, 'next_cursor' => $cursor,
            'limit' => max(1, count($ids)), 'authorized_count' => $total, 'open_count' => $total, 'won_count' => 0, 'lost_count' => 0,
            'totals_by_currency' => [(object) ['currency' => 'EUR', 'amount' => '999999999999999999999999999999999999.9999', 'weighted_amount' => '0.0000']]]]);

        return new Response(200, ['Content-Type' => 'application/json'], $result->toJson());
    }

    public static function detail(string $id = self::FIRST): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => self::opportunity($id)], JSON_THROW_ON_ERROR));
    }

    public static function error(int $status): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '9'], json_encode(['error' => ['type' => 'invalid_request_error',
            'code' => 'parameter_invalid', 'message' => 'Native Opportunity query rejected', 'param' => 'cursor', 'request_id' => 'req_opportunity73']], JSON_THROW_ON_ERROR));
    }
}
