<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

final class ExportUnconfirmedException extends \RuntimeException
{
    public readonly string $outcome;

    public function __construct(public readonly ExportIntent $intent, \Throwable $previous)
    {
        $this->outcome = 'unconfirmed';
        parent::__construct('Export outcome is unconfirmed; read the same original without repeating POST.', 0, $previous);
    }
}
