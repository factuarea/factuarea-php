<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Operations;

use Factuarea\Sdk\Models\Components\ServiceSlaHistory;

final readonly class GetServiceSlaHistoryResponseBody
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaHistory')]
    public ServiceSlaHistory $data;

    public function __construct(ServiceSlaHistory $data)
    {
        $this->data = $data;
    }
}
