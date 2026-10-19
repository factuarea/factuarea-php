<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

/** Retains the exact write tuple; recovery is possible only when its original server identities are known. */
final class UnconfirmedWriteException extends \RuntimeException
{
    public function __construct(public readonly OriginalIntent $originalIntent, \Throwable $previous)
    {
        parent::__construct('Task time write outcome is unconfirmed; retain the original intent and reconcile known original identities before another write.', 0, $previous);
    }
}
