<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class Service extends WireModel
{
    public const SCHEMA = Schemas::LIST_SERVICE;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'publication_version' => ['property' => 'publicationVersion', 'type' => ['scalar' => 'int']], 'public_id' => ['property' => 'publicId', 'type' => ['scalar' => 'string']], 'state' => ['property' => 'state', 'type' => ['scalar' => 'string']], 'host_id' => ['property' => 'hostId', 'type' => ['scalar' => 'string']], 'resource_id' => ['property' => 'resourceId', 'type' => ['scalar' => 'string']], 'schedule' => ['property' => 'schedule', 'type' => ['object' => true]], 'required_sources' => ['property' => 'requiredSources', 'type' => ['items' => ['scalar' => 'string']]], 'notice_version' => ['property' => 'noticeVersion', 'type' => ['scalar' => 'string']], 'hold_minutes' => ['property' => 'holdMinutes', 'type' => ['scalar' => 'int']], 'location' => ['property' => 'location', 'type' => ['scalar' => 'string']], 'source_statuses' => ['property' => 'sourceStatuses', 'type' => ['object' => true]]];

    /** @param list<string>|Omitted $requiredSources */
    public function __construct(public string $id, public int $version, public int|Omitted $publicationVersion = Omitted::Value, public string|Omitted $publicId = Omitted::Value, public string|Omitted $state = Omitted::Value, public string|Omitted $hostId = Omitted::Value, public string|Omitted $resourceId = Omitted::Value, public \stdClass|Omitted $schedule = Omitted::Value, public array|Omitted $requiredSources = Omitted::Value, public string|Omitted $noticeVersion = Omitted::Value, public int|Omitted $holdMinutes = Omitted::Value, public string|null|Omitted $location = Omitted::Value, public \stdClass|Omitted $sourceStatuses = Omitted::Value)
    {
        $this->validate();
    }
}
