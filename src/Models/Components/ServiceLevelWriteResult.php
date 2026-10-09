<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceLevelWriteResult
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $id;

    #[\Speakeasy\Serializer\Annotation\SerializedName('version')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $version;

    public function __construct(
        string $id,
        int $version,
    ) {
        $this->id = $id;
        $this->version = $version;
    }
}
