<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

/** Native controls have no receipt/key recovery; a current-state read is not original confirmation. */
final class UnconfirmedControlException extends \RuntimeException
{
    public function __construct(public readonly string $action, public readonly string $enrolmentId,
        public readonly int $expectedVersion, \Throwable $previous)
    {
        parent::__construct('Sequence control outcome is unconfirmed. Do not automatically resend the control.', 0, $previous);
    }
}
