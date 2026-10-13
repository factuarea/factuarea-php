<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ApplyRequest extends WireModel
{
    public const SCHEMA = Schemas::APPLY;
    public const FIELDS = [
        'expected_plan_version' => ['property' => 'expectedPlanVersion', 'type' => ['scalar' => 'int']],
        'preview_hash' => ['property' => 'previewHash', 'type' => ['scalar' => 'string']],
        'plan_token' => ['property' => 'planToken', 'type' => ['scalar' => 'string']],
        'resolutions' => ['property' => 'resolutions', 'type' => ['items' => ['model' => Resolution::class]]],
        'demo_selected' => ['property' => 'demoSelected', 'type' => ['scalar' => 'bool']],
        'confirmed' => ['property' => 'confirmed', 'type' => ['scalar' => 'bool']],
    ];

    /** @param list<Resolution> $resolutions */
    public function __construct(public readonly int $expectedPlanVersion, public readonly string $previewHash, public readonly string $planToken, public readonly array $resolutions, public readonly bool $demoSelected, public readonly bool $confirmed)
    {
        $this->validate();
        WireCodec::token($planToken);
        WireCodec::unique(array_map(static fn (Resolution $selection): string => $selection->itemId, $resolutions));
    }
}
