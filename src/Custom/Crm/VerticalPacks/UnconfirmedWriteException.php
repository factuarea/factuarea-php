<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

/** Retains original identity/key/wire; reconciling this tuple never redispatches a mutation. */
final class UnconfirmedWriteException extends \RuntimeException
{
    public function __construct(public readonly OriginalIntent $originalIntent, \Throwable $previous)
    {
        parent::__construct('Vertical pack write is unconfirmed; retain the exact original intent and use readonly recovery before another effect.', 0, $previous);
    }
}
