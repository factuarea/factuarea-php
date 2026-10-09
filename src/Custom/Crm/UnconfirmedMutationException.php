<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

/** A transport failure cannot establish whether the server committed the effect. */
final class UnconfirmedMutationException extends \RuntimeException
{
    public readonly string $outcome;

    public function __construct(
        public readonly string $operationId,
        public readonly string $idempotencyKey,
        \Throwable $previous,
    ) {
        $this->outcome = 'unconfirmed';
        parent::__construct('CRM mutation outcome is unconfirmed. Reconcile with the original idempotency key before any new intent.', 0, $previous);
    }
}
