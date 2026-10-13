<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class MissingOpportunity extends WireModel
{
    public const SCHEMA = Schemas::MISSING;

    public const FIELDS = ['opportunity' => ['property' => 'opportunity', 'type' => []]];

    public function __construct(
        public readonly null $opportunity,
    ) {
        $this->validate();
    }
}
