<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Operations;

use Factuarea\Sdk\Models\Components\ServiceLevelWriteResult;

final readonly class ServiceLevelWriteResponseBody
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceLevelWriteResult')]
    public ServiceLevelWriteResult $data;

    public function __construct(ServiceLevelWriteResult $data)
    {
        $this->data = $data;
    }
}
