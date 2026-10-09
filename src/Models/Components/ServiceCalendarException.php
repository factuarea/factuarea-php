<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceCalendarException
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('date')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $date;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceCalendarDayWindow> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('windows')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceCalendarDayWindow>')]
    public array $windows;

    /**
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceCalendarDayWindow>  $windows
     */
    public function __construct(
        string $date,
        array $windows,
    ) {
        $this->date = $date;
        $this->windows = $windows;
    }
}
