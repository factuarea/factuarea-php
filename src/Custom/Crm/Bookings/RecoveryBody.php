<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

/** Only the outer readonly lookup is serialized; the original inner wire remains byte-identical. */
final class RecoveryBody extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['original_wire_body'], 'properties' => ['original_wire_body' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 65536],
        'operation' => ['type' => 'string', 'enum' => ['booking.create_public', 'booking.reschedule_public', 'booking.cancel_public']]]];
    public const FIELDS = ['original_wire_body' => ['property' => 'originalWireBody', 'type' => ['scalar' => 'string']], 'operation' => ['property' => 'operation', 'type' => ['scalar' => 'string']]];

    public function __construct(#[\SensitiveParameter] public string $originalWireBody, public string|Omitted $operation = Omitted::Value)
    {
        $this->validate();
    }
}
