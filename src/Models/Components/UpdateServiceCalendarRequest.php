<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class UpdateServiceCalendarRequest
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $id;

    #[\Speakeasy\Serializer\Annotation\SerializedName('expected_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $expectedVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('name')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $name;

    #[\Speakeasy\Serializer\Annotation\SerializedName('timezone')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $timezone;

    #[\Speakeasy\Serializer\Annotation\SerializedName('mode')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $mode;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceCalendarWindow> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('windows')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceCalendarWindow>')]
    public array $windows;

    /** @var list<string> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('holidays')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>')]
    public array $holidays;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceCalendarException> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('exceptions')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceCalendarException>')]
    public array $exceptions;

    /**
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceCalendarWindow>  $windows
     * @param  list<string>  $holidays
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceCalendarException>  $exceptions
     */
    public function __construct(
        ?string $id,
        ?int $expectedVersion,
        string $name,
        string $timezone,
        string $mode,
        array $windows,
        array $holidays,
        array $exceptions,
    ) {
        $this->id = $id;
        $this->expectedVersion = $expectedVersion;
        $this->name = $name;
        $this->timezone = $timezone;
        $this->mode = $mode;
        $this->windows = $windows;
        $this->holidays = $holidays;
        $this->exceptions = $exceptions;
    }
}
