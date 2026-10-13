<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Pack extends WireModel
{
    public const SCHEMA = Schemas::PACK;
    public const FIELDS = [
        'id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'version' => ['property' => 'version', 'type' => ['scalar' => 'int']],
        'sector' => ['property' => 'sector', 'type' => ['scalar' => 'string']],
        'title' => ['property' => 'title', 'type' => ['model' => TranslatedText::class]],
        'description' => ['property' => 'description', 'type' => ['model' => TranslatedText::class]],
        'supports_demo' => ['property' => 'supportsDemo', 'type' => ['scalar' => 'bool']],
        'installation' => ['property' => 'installation', 'type' => ['model' => Installation::class]],
    ];

    public function __construct(public readonly string $id, public readonly int $version, public readonly string $sector, public readonly TranslatedText $title, public readonly TranslatedText $description, public readonly bool $supportsDemo, public readonly ?Installation $installation)
    {
        $this->validate();
        if ($installation !== null && $installation->packId !== $id) {
            throw new \InvalidArgumentException('The current native installation must belong to the same pack.');
        }
    }
}
