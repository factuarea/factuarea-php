<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceCalendarDayWindow
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('start_minute')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $startMinute;

    #[\Speakeasy\Serializer\Annotation\SerializedName('end_minute')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $endMinute;

    public function __construct(
        int $startMinute,
        int $endMinute,
    ) {
        $this->startMinute = $startMinute;
        $this->endMinute = $endMinute;
    }
}
