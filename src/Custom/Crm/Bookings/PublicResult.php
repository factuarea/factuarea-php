<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class PublicResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PUBLIC_RECEIPT]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => PublicReceipt::class]]];

    public function __construct(public PublicReceipt $data)
    {
        $this->validate();
    }
}
