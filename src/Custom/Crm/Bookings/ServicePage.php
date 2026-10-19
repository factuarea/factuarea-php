<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class ServicePage extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => ServicePageData::SCHEMA]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => ServicePageData::class]]];

    public function __construct(public ServicePageData $data)
    {
        $this->validate();
    }
}
