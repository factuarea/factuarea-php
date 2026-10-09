<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaHistory
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('subject_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $subjectId;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceSlaCycle> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('items')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceSlaCycle>')]
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
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceSlaCycle>  $items
     */
    public function __construct(
        string $subjectId,
        array $items,
        int $total,
        int $page,
        int $perPage,
    ) {
        $this->subjectId = $subjectId;
        $this->items = $items;
        $this->total = $total;
        $this->page = $page;
        $this->perPage = $perPage;
    }
}
