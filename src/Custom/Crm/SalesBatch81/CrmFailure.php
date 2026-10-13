<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

/** Closed transport facts only. No response body, user message, credential, request body or previous exception. */
class CrmFailure extends \RuntimeException
{
    public function __construct(public readonly ?int $status, public readonly string $machineCode,
        public readonly ?string $subcode = null, public readonly ?string $param = null,
        public readonly ?string $requestId = null, public readonly ?string $retryAfter = null, public readonly bool $unconfirmed = false)
    {
        parent::__construct('Native CRM request failed: '.$machineCode.'.');
    }
}
