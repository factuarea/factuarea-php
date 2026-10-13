<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PlaybookAnswer extends WireModel
{
    public const SCHEMA = Schemas::PLAYBOOK_ANSWER;

    public const FIELDS = ['definition_id' => ['property' => 'definitionId', 'type' => ['scalar' => 'string']], 'definition_version' => ['property' => 'definitionVersion', 'type' => ['scalar' => 'int']], 'key' => ['property' => 'key', 'type' => ['scalar' => 'string']], 'answer' => ['property' => 'answer', 'type' => []], 'evidence_ids' => ['property' => 'evidenceIds', 'type' => ['items' => ['scalar' => 'string']]]];

    /**
     * @param  string|bool|list<string>|null  $answer
     * @param  list<string>  $evidenceIds
     */
    public function __construct(
        public readonly string $definitionId,
        public readonly int $definitionVersion,
        public readonly string $key,
        public readonly string|bool|array|null $answer,
        public readonly array $evidenceIds,
    ) {
        $this->validate();
        if (trim($this->key) === '') {
            throw new \InvalidArgumentException('Playbook answer key cannot be blank.');
        }
    }
}
