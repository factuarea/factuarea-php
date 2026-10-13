<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class PublicReceipt extends WireModel
{
    public const SCHEMA = Schemas::PUBLIC_RECEIPT;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'status' => ['property' => 'status', 'type' => ['scalar' => 'string']], 'start_at' => ['property' => 'startAt', 'type' => ['scalar' => 'string']], 'end_at' => ['property' => 'endAt', 'type' => ['scalar' => 'string']], 'effect_id' => ['property' => 'effectId', 'type' => ['scalar' => 'string']], 'manage_token' => ['property' => 'manageToken', 'type' => ['scalar' => 'string']]];

    public function __construct(public string $id, public int $version, public string $status, public string $startAt, public string $endAt, public string $effectId, #[\SensitiveParameter] public string|Omitted $manageToken = Omitted::Value)
    {
        $this->validate();
        WireCodec::interval($this->startAt, $this->endAt);
    }
    /** @return array<string,string|int> */
    public function __debugInfo(): array
    {
        return ['id' => $this->id, 'version' => $this->version, 'status' => $this->status, 'effectId' => $this->effectId, 'manageToken' => '[redacted]'];
    }
}
