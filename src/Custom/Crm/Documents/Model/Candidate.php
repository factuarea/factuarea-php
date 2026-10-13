<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;
final readonly class Candidate
{
    private function __construct(public string $id, public string $type, public int $revision, public string $state, public ?string $label,
        public string $currency, public ?string $validFrom, public ?string $validUntil, public string $financialApplicability)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['id', 'type', 'revision', 'state', 'label', 'currency', 'valid_from', 'valid_until', 'financial_applicability']);
        $type = Wire::documentType($v['type']);
        if ($v['financial_applicability'] !== (in_array($type, ['quote', 'invoice'], true) ? 'applicable' : 'not_applicable')) {
            throw new InvalidArgumentException('Candidate applicability does not match its native type.');
        }

        return new self(Wire::uuid($v['id'], true), $type, Wire::integer($v['revision']), Wire::text($v['state'], 100), Wire::nullableText($v['label'], 255), Wire::currency($v['currency']), $v['valid_from'] === null ? null : Wire::date($v['valid_from']), $v['valid_until'] === null ? null : Wire::date($v['valid_until']), $v['financial_applicability']);
    }
}
