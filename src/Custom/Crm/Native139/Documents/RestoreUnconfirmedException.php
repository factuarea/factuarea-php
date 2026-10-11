<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\Documents;

/** An absent Original carrier cannot be replaced by Current or a second restore POST. */
final class RestoreUnconfirmedException extends \RuntimeException
{
    public readonly string $outcome;
    public readonly string $originalState;
    public readonly bool $canReadOriginal;

    public function __construct(public readonly RestoreIntent $intent, ?\Throwable $previous = null)
    {
        $this->outcome = 'unconfirmed';
        $this->originalState = 'unknown';
        $this->canReadOriginal = false;
        parent::__construct('Restore Original is unknown: Source143 has no separate lookup. Stop without resending or confirming from Current.', 0, $previous);
    }
}
