<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PlanItem extends WireModel
{
    public const SCHEMA = Schemas::ITEM;
    public const FIELDS = [
        'id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'kind' => ['property' => 'kind', 'type' => ['scalar' => 'string']],
        'machine_key' => ['property' => 'machineKey', 'type' => ['scalar' => 'string']],
        'label' => ['property' => 'label', 'type' => ['model' => TranslatedText::class]],
        'decision' => ['property' => 'decision', 'type' => ['scalar' => 'string']],
        'current_version' => ['property' => 'currentVersion', 'type' => ['scalar' => 'int']],
        'allowed_resolutions' => ['property' => 'allowedResolutions', 'type' => ['items' => ['scalar' => 'string']]],
        'current_display' => ['property' => 'currentDisplay', 'type' => ['model' => TranslatedText::class]],
        'proposed_display' => ['property' => 'proposedDisplay', 'type' => ['model' => TranslatedText::class]],
    ];

    /** @param list<string> $allowedResolutions */
    public function __construct(public readonly string $id, public readonly string $kind, public readonly string $machineKey, public readonly TranslatedText $label, public readonly string $decision, public readonly ?int $currentVersion, public readonly array $allowedResolutions, public readonly ?TranslatedText $currentDisplay, public readonly TranslatedText $proposedDisplay)
    {
        $this->validate();
        if (($decision === 'add') !== ($currentVersion === null) || ($currentVersion === null) !== ($currentDisplay === null)
            || ($decision !== 'conflict' && $allowedResolutions !== []) || ($decision === 'conflict' && !in_array('keep', $allowedResolutions, true))) {
            throw new \InvalidArgumentException('Native item decision, current value and allowed resolutions must agree.');
        }
    }
}
