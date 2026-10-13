<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class TranslatedText extends WireModel
{
    public const SCHEMA = Schemas::TEXT;
    public const FIELDS = [
        'es' => ['property' => 'es', 'type' => ['scalar' => 'string']],
        'en' => ['property' => 'en', 'type' => ['scalar' => 'string']],
        'ca' => ['property' => 'ca', 'type' => ['scalar' => 'string']],
    ];

    public function __construct(public readonly string $es, public readonly string $en, public readonly string $ca)
    {
        $this->validate();
        foreach ([$es, $en, $ca] as $text) {
            if (trim($text) === '') {
                throw new \InvalidArgumentException('Native translated text cannot be blank.');
            }
        }
    }
}
