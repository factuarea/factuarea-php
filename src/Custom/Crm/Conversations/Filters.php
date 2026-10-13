<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

/** Native filter JSON deliberately retains mailbox_uuid/target_uuid even on V1; these are selection, never authority. */
final class Filters extends WireModel
{
    public const SCHEMA = Schemas::FILTERS;
    public const FIELDS = ['mailbox_uuid' => ['property' => 'mailboxId', 'type' => ['scalar' => 'string']],
        'state' => ['property' => 'state', 'type' => ['scalar' => 'string']], 'search' => ['property' => 'search', 'type' => ['scalar' => 'string']],
        'target_type' => ['property' => 'targetType', 'type' => ['scalar' => 'string']], 'target_uuid' => ['property' => 'targetId', 'type' => ['scalar' => 'string']],
        'sort' => ['property' => 'sort', 'type' => ['scalar' => 'string']]];

    public function __construct(public readonly string|Omitted $mailboxId = Omitted::Value, public readonly string|Omitted $state = Omitted::Value,
        public readonly string|Omitted $search = Omitted::Value, public readonly string|Omitted $targetType = Omitted::Value,
        public readonly string|Omitted $targetId = Omitted::Value, public readonly string|Omitted $sort = Omitted::Value)
    {
        $this->validate();
        Validation::rawFilters($this->toJson());
    }
}
