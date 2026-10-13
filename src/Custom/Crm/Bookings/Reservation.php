<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class Reservation extends WireModel
{
    public const SCHEMA = Schemas::RESERVATION;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'service_id' => ['property' => 'serviceId', 'type' => ['scalar' => 'string']], 'host_id' => ['property' => 'hostId', 'type' => ['scalar' => 'string']], 'resource_id' => ['property' => 'resourceId', 'type' => ['scalar' => 'string']], 'status' => ['property' => 'status', 'type' => ['scalar' => 'string']], 'start_at' => ['property' => 'startAt', 'type' => ['scalar' => 'string']], 'end_at' => ['property' => 'endAt', 'type' => ['scalar' => 'string']], 'projection_status' => ['property' => 'projectionStatus', 'type' => ['scalar' => 'string']]];

    public function __construct(public string $id, public int $version, public string|Omitted $serviceId = Omitted::Value, public string|Omitted $hostId = Omitted::Value, public string|Omitted $resourceId = Omitted::Value, public string|Omitted $status = Omitted::Value, public string|Omitted $startAt = Omitted::Value, public string|Omitted $endAt = Omitted::Value, public string|Omitted $projectionStatus = Omitted::Value)
    {
        $this->validate();
        foreach ([$this->startAt, $this->endAt] as $instant) {
            if (is_string($instant)) {
                WireCodec::instant($instant);
            }
        }
    }
}
