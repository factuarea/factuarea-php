<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Stages;

final class UpdateUnconfirmedException extends \RuntimeException
{
    public readonly string $outcome;
    public readonly bool $canRetry;

    public function __construct(public readonly UpdateIntent $intent, \Throwable $previous)
    {
        $this->outcome = 'unconfirmed';
        $this->canRetry = false;
        parent::__construct('Read the retained stage Original; a Current snapshot cannot confirm this update.', 0, $previous);
    }
}
