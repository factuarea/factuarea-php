<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

/** Current denial/ambiguous ACK never substitutes a new key, head or intent. */
final class UnconfirmedWriteException extends \RuntimeException
{
    public function __construct(public readonly OriginalIntent $originalIntent, \Throwable $previous)
    {
        parent::__construct('Booking completion is unconfirmed; retain the original intent for its native receipt lookup.', 0, $previous);
    }
}
