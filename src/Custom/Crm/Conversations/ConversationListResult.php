<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ConversationListResult extends WireModel
{
    public const SCHEMA = Schemas::LIST_RESULT;

    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\ConversationPage']], 'meta' => ['property' => 'meta', 'type' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\ReadMeta']]];

    public function __construct(
        public readonly ConversationPage $data,
        public readonly ReadMeta $meta,
    ) {
        $this->validate();
        foreach ($data->items as $item) {
            Validation::headMask($item, $meta);
        }
    }
}
