<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use InvalidArgumentException;

/** Minimal native manual lead, with explicit omitted/null semantics; identity is assigned by the server. */
final readonly class CreateLeadRequest
{
    public function __construct(public string|null|Omitted $name = Omitted::Value, public string|null|Omitted $email = Omitted::Value,
        public string|null|Omitted $phone = Omitted::Value, public string|null|Omitted $mobile = Omitted::Value,
        public string|null|Omitted $companyName = Omitted::Value, public string|null|Omitted $notes = Omitted::Value,
        public string|null|Omitted $ownerId = Omitted::Value, public string|null|Omitted $teamId = Omitted::Value)
    {
        $identified = false;
        foreach (['name' => [$name, 255], 'email' => [$email, 191], 'phone' => [$phone, 30], 'mobile' => [$mobile, 30],
            'company_name' => [$companyName, 255], 'notes' => [$notes, 5000]] as $field => [$value,$maximum]) {
            if (!$value instanceof Omitted) {
                Wire::text($value, $maximum);
                if (in_array($field, ['name', 'email', 'phone', 'mobile'], true) && is_string($value) && trim($value) !== '') {
                    $identified = true;
                }
            }
        }
        if (!$identified) {
            throw new InvalidArgumentException('A native lead requires an explicit name, email or phone.');
        }
        foreach ([$ownerId, $teamId] as $id) {
            if (is_string($id)) {
                Wire::leadUuid($id);
            }
        }
    }
    public function toArray(): array
    {
        return Wire::optional(['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'mobile' => $this->mobile,
            'company_name' => $this->companyName, 'notes' => $this->notes, 'owner_id' => $this->ownerId, 'team_id' => $this->teamId]) + ['source' => 'manual'];
    }
}
