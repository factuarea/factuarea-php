<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService;

use Factuarea\Sdk\Models\Errors\APIException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/** Recover through the same POST/key/body, retaining the original CAS even if the current ticket has moved on. */
final class TicketWriteUnconfirmed extends APIException
{
    public function __construct(public readonly TicketWriteIntent $intent, ?ResponseInterface $response = null, public readonly ?Throwable $cause = null)
    {
        parent::__construct('The original service ticket result is unconfirmed. Retain its intent and idempotency key.',
            $response?->getStatusCode() ?? 0, $response === null ? '' : (string) $response->getBody(), $response);
    }
}
