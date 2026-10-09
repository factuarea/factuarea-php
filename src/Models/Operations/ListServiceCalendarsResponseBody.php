<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Operations;

use Factuarea\Sdk\Models\Components\ServiceCalendarList;

final readonly class ListServiceCalendarsResponseBody
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceCalendarList')]
    public ServiceCalendarList $data;

    public function __construct(ServiceCalendarList $data)
    {
        $this->data = $data;
    }
}
