<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

final class ExportNotReadyException extends \RuntimeException
{
    public function __construct(public readonly ExportJob $job)
    {
        parent::__construct('The native export is '.$job->status.'; this original does not expose a ready binary.');
    }
}
