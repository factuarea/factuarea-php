<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

/** V1 id aliases, native typed-field predicates and opaque server-owned current filtering authority. */
final class Filters extends WireModel
{
    public const SCHEMA = Schemas::FILTERS;

    public const FIELDS = ['status' => ['property' => 'status', 'type' => ['scalar' => 'string']], 'pipeline_id' => ['property' => 'pipelineId', 'type' => ['scalar' => 'string']], 'stage_id' => ['property' => 'stageId', 'type' => ['scalar' => 'string']], 'owner_id' => ['property' => 'ownerId', 'type' => ['scalar' => 'string']], 'team_id' => ['property' => 'teamId', 'type' => ['scalar' => 'string']], 'contact_id' => ['property' => 'contactId', 'type' => ['scalar' => 'string']], 'lead_id' => ['property' => 'leadId', 'type' => ['scalar' => 'string']], 'phone' => ['property' => 'phone', 'type' => ['scalar' => 'string']], 'source' => ['property' => 'source', 'type' => ['scalar' => 'string']], 'created_from' => ['property' => 'createdFrom', 'type' => ['scalar' => 'string']], 'created_to' => ['property' => 'createdTo', 'type' => ['scalar' => 'string']], 'expected_close_from' => ['property' => 'expectedCloseFrom', 'type' => ['scalar' => 'string']], 'expected_close_to' => ['property' => 'expectedCloseTo', 'type' => ['scalar' => 'string']], 'typed_filters' => ['property' => 'typedFilters', 'type' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Opportunities\\TypedFilters']], 'sort' => ['property' => 'sort', 'type' => ['scalar' => 'string']], 'direction' => ['property' => 'direction', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly string|Omitted $status = Omitted::Value,
        public readonly string|Omitted $pipelineId = Omitted::Value,
        public readonly string|Omitted $stageId = Omitted::Value,
        public readonly string|Omitted $ownerId = Omitted::Value,
        public readonly string|Omitted $teamId = Omitted::Value,
        public readonly string|Omitted $contactId = Omitted::Value,
        public readonly string|Omitted $leadId = Omitted::Value,
        public readonly string|Omitted $phone = Omitted::Value,
        public readonly string|Omitted $source = Omitted::Value,
        public readonly string|Omitted $createdFrom = Omitted::Value,
        public readonly string|Omitted $createdTo = Omitted::Value,
        public readonly string|Omitted $expectedCloseFrom = Omitted::Value,
        public readonly string|Omitted $expectedCloseTo = Omitted::Value,
        public readonly TypedFilters|Omitted $typedFilters = Omitted::Value,
        public readonly string|Omitted $sort = Omitted::Value,
        public readonly string|Omitted $direction = Omitted::Value,
    ) {
        $this->validate();
        foreach ([$this->createdFrom, $this->createdTo, $this->expectedCloseFrom, $this->expectedCloseTo] as $date) {
            if ($date !== Omitted::Value) {
                Validation::date($date);
            }
        }
        foreach ([[$this->createdFrom, $this->createdTo], [$this->expectedCloseFrom, $this->expectedCloseTo]] as [$from, $to]) {
            if ($from !== Omitted::Value && $to !== Omitted::Value && $from > $to) {
                throw new \InvalidArgumentException('Opportunity filter date range is inverted.');
            }
        }
        if ($this->source !== Omitted::Value && trim($this->source) === '') {
            throw new \InvalidArgumentException('Opportunity source cannot be blank.');
        }
        if (strlen($this->toJson()) > 262144) {
            throw new \InvalidArgumentException('Native Opportunity filters exceed 262144 bytes.');
        }
    }
}
