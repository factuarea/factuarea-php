<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;
final readonly class FinancialSummary
{
    /** @param list<array{currency:string,forecast:string,quoted:string,invoiced:string,paid:string}> $currencies */
    private function __construct(public string $opportunityId, public int $version, public array $currencies)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['opportunity_id', 'version', 'currencies']);
        $currencies = [];
        $seen = [];
        foreach (Wire::items($v['currencies']) as $item) {
            $r = Wire::record($item, ['currency', 'forecast', 'quoted', 'invoiced', 'paid']);
            $currency = Wire::currency($r['currency']);
            if (in_array($currency, $seen, true)) {
                throw new InvalidArgumentException('Currency buckets cannot be repeated or silently aggregated.');
            }$seen[] = $currency;
            $currencies[] = ['currency' => $currency, 'forecast' => Wire::decimal($r['forecast'], 4), 'quoted' => Wire::decimal($r['quoted']), 'invoiced' => Wire::decimal($r['invoiced']), 'paid' => Wire::decimal($r['paid'])];
        }

        return new self(Wire::uuid($v['opportunity_id']), Wire::integer($v['version']), $currencies);
    }
}
