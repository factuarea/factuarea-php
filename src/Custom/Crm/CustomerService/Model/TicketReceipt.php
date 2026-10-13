<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Model;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketOperation;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWriteIntent;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

/** Public projection of the original private owner receipt. It carries no attachment identity or delivery claim. */
final readonly class TicketReceipt
{
    private function __construct(public string $id, public int $version)
    {
    }
    public static function fromIntent(TicketWriteIntent $intent, mixed $wire): self
    {
        $value = TicketWire::record($wire, ['id', 'version']);
        $receipt = new self(Wire::uuid($value['id']), Wire::integer($value['version']));
        $versions = $intent->operation === TicketOperation::MoveToStage ? [$intent->expectedVersion, $intent->expectedVersion + 1] : [$intent->expectedVersion + 1];
        if (strcasecmp($receipt->id, $intent->ticketId) !== 0 || !in_array($receipt->version, $versions, true)) {
            throw new InvalidArgumentException('The ticket receipt must retain the original identity and operation CAS.');
        }

        return $receipt;
    }
}
