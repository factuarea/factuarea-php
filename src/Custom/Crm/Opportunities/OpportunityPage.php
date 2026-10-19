<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class OpportunityPage extends WireModel
{
    public const SCHEMA = Schemas::PAGE;

    public const FIELDS = ['items' => ['property' => 'items', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Opportunities\\Opportunity']]], 'next_cursor' => ['property' => 'nextCursor', 'type' => ['scalar' => 'string']], 'limit' => ['property' => 'limit', 'type' => ['scalar' => 'int']], 'authorized_count' => ['property' => 'authorizedCount', 'type' => ['scalar' => 'int']], 'open_count' => ['property' => 'openCount', 'type' => ['scalar' => 'int']], 'won_count' => ['property' => 'wonCount', 'type' => ['scalar' => 'int']], 'lost_count' => ['property' => 'lostCount', 'type' => ['scalar' => 'int']], 'totals_by_currency' => ['property' => 'totalsByCurrency', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Opportunities\\CurrencyTotal']]]];

    /**
     * @param  list<Opportunity>  $items
     * @param  list<CurrencyTotal>|null  $totalsByCurrency
     */
    public function __construct(
        public readonly array $items,
        public readonly ?string $nextCursor,
        public readonly int $limit,
        public readonly int $authorizedCount,
        public readonly int $openCount,
        public readonly int $wonCount,
        public readonly int $lostCount,
        public readonly ?array $totalsByCurrency,
    ) {
        $this->validate();
        if (count($items) > $limit || $authorizedCount < count($items)
            || $openCount > $authorizedCount || $wonCount > $authorizedCount - $openCount
            || $lostCount !== $authorizedCount - $openCount - $wonCount
            || ($nextCursor !== null && (trim($nextCursor) === '' || $items === []))) {
            throw new \InvalidArgumentException('Native Opportunity page metadata is inconsistent.');
        }
        $seen = [];
        foreach ($items as $item) {
            Validation::unique($seen, $item->id);
        }
        $seen = [];
        foreach ($totalsByCurrency ?? [] as $total) {
            Validation::unique($seen, $total->currency);
        }
    }
}
