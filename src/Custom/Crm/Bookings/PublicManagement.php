<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class PublicManagement extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PUBLIC_HEAD]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['object' => true]]];

    /** @param object{id:string,version:int,status:string,start_at:string,end_at:string}&\stdClass $data */
    public function __construct(public \stdClass $data)
    {
        $this->validate();
    }
}
