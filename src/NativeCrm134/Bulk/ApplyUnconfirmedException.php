<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Bulk;

final class ApplyUnconfirmedException extends \RuntimeException
{
    public readonly string $outcome;
    public readonly bool $canRetry;

    public function __construct(public readonly ApplyIntent $intent, \Throwable $previous)
    {
        $this->outcome = 'unconfirmed';
        $this->canRetry = false;
        parent::__construct('Recover every retained bulk item through its native Original GET before any new intent.', 0, $previous);
    }
}
