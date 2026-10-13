<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ReceiptData extends WireModel
{
    public const SCHEMA = Schemas::RECEIPT;
    public const FIELDS = ['ticket_id' => ['property' => 'ticketId', 'type' => ['scalar' => 'string']], 'ticket_version' => ['property' => 'ticketVersion', 'type' => ['scalar' => 'int']], 'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']], 'status' => ['property' => 'status', 'type' => ['scalar' => 'string']], 'effect_id' => ['property' => 'effectId', 'type' => ['scalar' => 'string']], 'operation' => ['property' => 'operation', 'type' => ['scalar' => 'string']], 'confirmed_at' => ['property' => 'confirmedAt', 'type' => ['scalar' => 'string']], 'result' => ['property' => 'result', 'type' => ['model' => ReceiptResultData::class]]];

    public function __construct(public string $ticketId, public int $ticketVersion, public string $authorizationVersion, public string $status, public string $effectId, public string $operation, public ?string $confirmedAt, public ReceiptResultData $result)
    {
        $this->validate();
    }
}
