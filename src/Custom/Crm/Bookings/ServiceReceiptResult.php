<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class ServiceReceiptResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => ['anyOf' => [Schemas::SERVICE, ['type' => 'null']]]]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => Service::class]]];

    public function __construct(public ?Service $data)
    {
        $this->validate();
    }
}
