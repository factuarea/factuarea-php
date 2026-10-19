<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class ReservationPage extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => ReservationPageData::SCHEMA]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => ReservationPageData::class]]];

    public function __construct(public ReservationPageData $data)
    {
        $this->validate();
    }
}
