<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

/** Native owner identity/revision and exact fiscal amounts; non-fiscal amounts remain null. */
final readonly class NativeDocument
{
    private function __construct(public string $id, public string $type, public int $version, public string $state, public ?string $number,
        public string $currency, public ?string $quotedAmount, public ?string $invoicedAmount, public ?string $paidAmount,
        public string $financialApplicability, public ?string $validFrom, public ?string $validUntil)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['id', 'type', 'version', 'state', 'number', 'currency', 'quoted_amount', 'invoiced_amount', 'paid_amount', 'financial_applicability', 'valid_from', 'valid_until']);
        $type = Wire::documentType($v['type']);
        $fiscal = in_array($type, ['quote', 'invoice'], true);
        if ($v['financial_applicability'] !== ($fiscal ? 'applicable' : 'not_applicable')) {
            throw new InvalidArgumentException('Native financial applicability does not match its document owner.');
        }
        $amounts = [];
        foreach (['quoted_amount', 'invoiced_amount', 'paid_amount'] as $key) {
            if ($fiscal) {
                $amounts[$key] = Wire::decimal($v[$key]);
            } elseif ($v[$key] !== null) {
                throw new InvalidArgumentException('Non-fiscal owner amounts must remain null.');
            } else {
                $amounts[$key] = null;
            }
        }

        return new self(Wire::uuid($v['id'], true), $type, Wire::integer($v['version']), Wire::text($v['state'], 100), Wire::nullableText($v['number'], 100), Wire::currency($v['currency']),
            $amounts['quoted_amount'], $amounts['invoiced_amount'], $amounts['paid_amount'], $v['financial_applicability'], $v['valid_from'] === null ? null : Wire::date($v['valid_from']), $v['valid_until'] === null ? null : Wire::date($v['valid_until']));
    }
}
