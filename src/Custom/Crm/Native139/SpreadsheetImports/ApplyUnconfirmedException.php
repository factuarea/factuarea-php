<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports;

final class ApplyUnconfirmedException extends \RuntimeException
{
    public readonly string $outcome;

    public function __construct(public readonly ApplyIntent $intent, \Throwable $previous)
    {
        $this->outcome = 'unconfirmed';
        parent::__construct('Apply outcome is unconfirmed. Read the same retained Original; do not resend the POST.', 0, $previous);
    }
}
