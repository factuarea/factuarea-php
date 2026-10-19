<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class PublicConfiguration extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PUBLIC_CONFIGURATION]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['object' => true]]];

    /** @param object{public_id:string,publication_version:int,business_timezone:string,is_sandbox:bool,service_display:string,duration_minutes:int,buffer_before:int,buffer_after:int,notice_version:string,notice_copy:string,captcha:object{provider:string,site_key?:string}}&\stdClass $data */
    public function __construct(public \stdClass $data)
    {
        $this->validate();
    }
}
