<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceCalendarList
{
    /** @var list<\Factuarea\Sdk\Models\Components\ServiceCalendar> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('items')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceCalendar>')]
    public array $items;

    #[\Speakeasy\Serializer\Annotation\SerializedName('total')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $total;

    #[\Speakeasy\Serializer\Annotation\SerializedName('page')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $page;

    #[\Speakeasy\Serializer\Annotation\SerializedName('per_page')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $perPage;

    /**
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceCalendar>  $items
     */
    public function __construct(
        array $items,
        int $total,
        int $page,
        int $perPage,
    ) {
        $this->items = $items;
        $this->total = $total;
        $this->page = $page;
        $this->perPage = $perPage;
    }
}
