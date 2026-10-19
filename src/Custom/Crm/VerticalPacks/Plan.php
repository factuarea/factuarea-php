<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Plan extends WireModel
{
    public const SCHEMA = Schemas::PLAN;
    public const FIELDS = [
        'id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'version' => ['property' => 'version', 'type' => ['scalar' => 'int']],
        'pack_id' => ['property' => 'packId', 'type' => ['scalar' => 'string']],
        'pack_version' => ['property' => 'packVersion', 'type' => ['scalar' => 'int']],
        'preview_hash' => ['property' => 'previewHash', 'type' => ['scalar' => 'string']],
        'plan_token' => ['property' => 'planToken', 'type' => ['scalar' => 'string']],
        'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']],
        'sandbox' => ['property' => 'sandbox', 'type' => ['scalar' => 'bool']],
        'demo_selected' => ['property' => 'demoSelected', 'type' => ['scalar' => 'bool']],
        'items' => ['property' => 'items', 'type' => ['items' => ['model' => PlanItem::class]]],
        'blocking_codes' => ['property' => 'blockingCodes', 'type' => ['items' => ['scalar' => 'string']]],
    ];

    /** @param list<PlanItem> $items
     * @param list<string> $blockingCodes */
    public function __construct(public readonly string $id, public readonly int $version, public readonly string $packId, public readonly int $packVersion, public readonly string $previewHash, public readonly string $planToken, public readonly string $authorizationVersion, public readonly bool $sandbox, public readonly bool $demoSelected, public readonly array $items, public readonly array $blockingCodes)
    {
        $this->validate();
        WireCodec::token($planToken);
        WireCodec::unique(array_map(static fn (PlanItem $item): string => $item->id, $items));
        if (trim($authorizationVersion) === '' || ($demoSelected && !$sandbox)) {
            throw new \InvalidArgumentException('An explicit demo selection requires the original sandbox plan.');
        }
    }
}
