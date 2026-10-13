<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class PublicAvailability extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PUBLIC_AVAILABILITY]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['object' => true]]];

    /** @param object{public_id:string,publication_version:int,business_timezone:string,visitor_timezone:string,slots:list<object{start_at:string,end_at:string}>,as_of:string,notice_version:string,is_sandbox:bool}&\stdClass $data */
    public function __construct(public \stdClass $data)
    {
        $this->validate();
    }
}
