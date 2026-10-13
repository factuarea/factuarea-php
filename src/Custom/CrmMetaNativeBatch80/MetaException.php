<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\CrmMetaNativeBatch80;

/** Error carries closed facts only; no provider body, code, state or credentials. */
final class MetaException extends \RuntimeException
{
    public function __construct(public readonly ?int $status, public readonly string $reason, public readonly ?string $requestId = null, public readonly ?int $retryAfter = null, public readonly bool $unconfirmed = false)
    {
        parent::__construct('Native Meta request failed: '.$reason.'.');
    }
}
