<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Operations;

use Factuarea\Sdk\Models\Components\ServiceSlaStatus;

final readonly class GetServiceSlaStatusResponseBody
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaStatus')]
    public ServiceSlaStatus $data;

    public function __construct(ServiceSlaStatus $data)
    {
        $this->data = $data;
    }
}
