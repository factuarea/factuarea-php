<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ReadMeta extends WireModel
{
    public const SCHEMA = Schemas::META;

    public const FIELDS = ['authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']], 'readable_fields' => ['property' => 'readableFields', 'type' => ['items' => ['scalar' => 'string']]], 'filterable_fields' => ['property' => 'filterableFields', 'type' => ['items' => ['scalar' => 'string']]], 'sortable_fields' => ['property' => 'sortableFields', 'type' => ['items' => ['scalar' => 'string']]]];

    /**
     * @param  list<string>  $readableFields
     * @param  list<string>  $filterableFields
     * @param  list<string>  $sortableFields
     */
    public function __construct(
        public readonly string $authorizationVersion,
        public readonly array $readableFields,
        public readonly array $filterableFields,
        public readonly array $sortableFields,
    ) {
        $this->validate();
        if (trim($authorizationVersion) === '' || array_diff(['uuid', 'version', 'mailbox_uuid', 'status'], $readableFields) !== []) {
            throw new \InvalidArgumentException('Native current read metadata is incomplete.');
        }
    }
}
