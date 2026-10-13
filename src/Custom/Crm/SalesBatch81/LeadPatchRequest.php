<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use InvalidArgumentException;

final readonly class LeadPatchRequest
{
    public function __construct(public int $expectedVersion, public string|null|Omitted $name = Omitted::Value,
        public string|null|Omitted $email = Omitted::Value, public string|null|Omitted $phone = Omitted::Value,
        public string|null|Omitted $mobile = Omitted::Value, public string|null|Omitted $companyName = Omitted::Value,
        public string|null|Omitted $notes = Omitted::Value)
    {
        if ($expectedVersion < 1 || $expectedVersion > 4294967295) {
            throw new InvalidArgumentException('The actual lead CAS must be between 1 and 4294967295.');
        }
        foreach (['name' => [$name, 255], 'email' => [$email, 191], 'phone' => [$phone, 30], 'mobile' => [$mobile, 30],
            'company_name' => [$companyName, 255], 'notes' => [$notes, 5000]] as [$value,$maximum]) {
            if (!$value instanceof Omitted) {
                Wire::text($value, $maximum);
            }
        }
    }
    public function toArray(): array
    {
        return ['expected_version' => $this->expectedVersion] + Wire::optional(['name' => $this->name, 'email' => $this->email,
            'phone' => $this->phone, 'mobile' => $this->mobile, 'company_name' => $this->companyName, 'notes' => $this->notes]);
    }
}
