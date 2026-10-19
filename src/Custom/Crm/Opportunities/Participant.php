<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Participant extends WireModel
{
    public const SCHEMA = Schemas::PARTICIPANT;

    public const FIELDS = ['person_id' => ['property' => 'personId', 'type' => ['scalar' => 'string']], 'role' => ['property' => 'role', 'type' => ['scalar' => 'string']], 'credit_percentage' => ['property' => 'creditPercentage', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly string $personId,
        public readonly string $role,
        public readonly ?string $creditPercentage,
    ) {
        $this->validate();
        if (trim($this->role) === '') {
            throw new \InvalidArgumentException('Participant role cannot be blank.');
        }
    }
}
