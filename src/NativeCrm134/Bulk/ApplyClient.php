<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Bulk;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\NativeCrm134\BulkOperations;
use Factuarea\Sdk\NativeCrm134\Model;

final class ApplyClient
{
    /** Process-local resend fence, never a batch ledger or authority cache. @var array<string, string> */
    private static array $issued = [];

    public function __construct(private readonly BulkOperations $native)
    {
    }

    /** @return CrmResponse<Model\ApplyServiceTicketBulkActionResult>|list<CrmResponse<Model\GetOriginalServiceTicketBulkItemResult>> */
    public function apply(ApplyIntent $intent): CrmResponse|array
    {
        if (isset(self::$issued[$intent->idempotencyKey])
            && ! hash_equals(self::$issued[$intent->idempotencyKey], $intent->originalBodySha256)) {
            throw new \InvalidArgumentException('A known bulk key cannot replace its original selection, action, CAS or payload.');
        }
        if ($intent->isReadbackOnly() || isset(self::$issued[$intent->idempotencyKey])) {
            return $this->originals($intent);
        }
        $request = $intent->request();
        self::$issued[$intent->idempotencyKey] = $intent->originalBodySha256;
        try {
            $response = $this->native->applyServiceTicketBulkAction($request);
            $data = $response->body->data;
            if ($data->action !== $request->body->action || $data->total !== count($request->body->items)
                || count($data->results) !== count($request->body->items)) {
                throw new \UnexpectedValueException('The native bulk response must retain the reviewed action and selection.');
            }
            $counts = ['confirmed' => 0, 'rejected' => 0, 'unknown' => 0];
            foreach ($data->results as $index => $result) {
                $item = $request->body->items[$index];
                if ($result->index !== $index || $result->id !== $item->id || $result->expectedVersion !== $item->expectedVersion) {
                    throw new \UnexpectedValueException('Each native bulk result must retain its original index, ticket and CAS.');
                }
                $counts[$result->state]++;
            }
            if ($data->confirmed !== $counts['confirmed'] || $data->rejected !== $counts['rejected'] || $data->unknown !== $counts['unknown']) {
                throw new \UnexpectedValueException('Native bulk totals must describe the factual per-item states.');
            }

            return $response;
        } catch (\Throwable $failure) {
            throw new ApplyUnconfirmedException($intent, $failure);
        }
    }

    /** Native item states stay separate; unknown never permits another POST. @return list<CrmResponse<Model\GetOriginalServiceTicketBulkItemResult>> */
    public function originals(ApplyIntent $intent): array
    {
        $responses = [];
        foreach ($intent->originalRequests() as $request) {
            $response = $this->native->getOriginalServiceTicketBulkItem($request);
            if ($response->body->data->id !== $request->ticket || $response->body->data->expectedVersion !== $request->expectedVersion) {
                throw new \UnexpectedValueException('The native bulk Original must identify the retained ticket and CAS.');
            }
            $responses[] = $response;
        }

        return $responses;
    }
}
