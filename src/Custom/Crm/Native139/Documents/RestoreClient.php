<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\Documents;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Native139\Documents;
use Factuarea\Sdk\Custom\Crm\Native139\Model;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;

final class RestoreClient
{
    /** Process-local resend fence, never an authorization cache. @var array<string,string> */
    private static array $issued = [];

    public function __construct(private readonly Documents $native)
    {
    }

    /** @return CrmResponse<Model\CrmRestoreCommercialDocumentResult> */
    public function restore(RestoreIntent $intent): CrmResponse
    {
        $identity = $intent->document."\0".$intent->idempotencyKey;
        if (isset(self::$issued[$identity]) && ! hash_equals(self::$issued[$identity], $intent->originalBodySha256)) {
            throw new \InvalidArgumentException('A known restore intent cannot replace its original body or key.');
        }
        if ($intent->isReadbackOnly() || isset(self::$issued[$identity])) {
            throw new RestoreUnconfirmedException($intent);
        }
        $request = $intent->request();
        self::$issued[$identity] = $intent->originalBodySha256;
        try {
            $response = $this->native->crmRestoreCommercialDocument($request);
            if ($response->body->data->id !== $intent->document) {
                throw new \UnexpectedValueException('The native restore receipt must identify the reviewed document.');
            }

            return $response;
        } catch (\Throwable $failure) {
            $status = $failure instanceof APIException ? $failure->statusCode
                : ($failure instanceof ErrorThrowable ? ($failure->container->rawResponse?->getStatusCode() ?? 0) : 0);
            if ($status >= 400 && $status < 500 && $status !== 408) {
                throw $failure;
            }
            throw new RestoreUnconfirmedException($intent, $failure);
        }
    }
}
