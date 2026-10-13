<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

/** Reconcile using GET with this intent's original key. Never manufacture another intent/key. */
final class UnconfirmedWriteException extends \RuntimeException
{
    public function __construct(public readonly OriginalIntent $originalIntent, \Throwable $previous)
    {
        parent::__construct('Sequence write outcome is unconfirmed; read its original result before another write.', 0, $previous);
    }
}
