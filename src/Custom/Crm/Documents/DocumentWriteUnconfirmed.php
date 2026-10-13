<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;
use Factuarea\Sdk\Models\Errors\APIException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/** Recovery uses the same operation/key/body. This exception never authorizes a fresh dispatch. */
final class DocumentWriteUnconfirmed extends APIException
{
    public function __construct(public readonly DocumentWriteIntent $intent, ?ResponseInterface $response = null, public readonly ?Throwable $cause = null)
    {
        parent::__construct('The original document result is unconfirmed. Retain its intent and idempotency key.', $response?->getStatusCode() ?? 0, $response === null ? '' : (string) $response->getBody(), $response);
    }
}
