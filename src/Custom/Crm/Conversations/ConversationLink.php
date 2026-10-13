<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ConversationLink extends WireModel
{
    public const SCHEMA = Schemas::LINK;

    public const FIELDS = ['type' => ['property' => 'type', 'type' => ['scalar' => 'string']], 'id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'state' => ['property' => 'state', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly string $type,
        public readonly string $id,
        public readonly string $state,
    ) {
        $this->validate();
        // Identity/state are facts only; an automatic suggestion never becomes a confirmed link.
    }
}
