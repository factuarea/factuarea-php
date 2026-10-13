<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;

/** Eight native V1 operations sharing the configured SDK transport/security/hooks. */
final readonly class TaskTimeBillingClient
{
    /** @param array<string,string> $headers */
    public function __construct(private Transport $transport, private RequiredPaths $paths, private array $headers = [])
    {
    }

    public function withHeaders(?string $xActiveProfile = null, ?string $factuareaVersion = null): self
    {
        $headers = $this->headers;
        foreach (['X-Active-Profile' => $xActiveProfile, 'Factuarea-Version' => $factuareaVersion] as $name => $value) {
            if ($value !== null) {
                if ($value === '' || preg_match('/[\x00-\x1f\x7f]/', $value) === 1) {
                    throw new \InvalidArgumentException('Invalid native header value.');
                }
                $headers[$name] = $value;
            }
        }

        return new self($this->transport, $this->paths, $headers);
    }

    /** @return CrmResponse<ReceiptResult> */
    public function linkServiceTicketTask(OriginalIntent $intent): CrmResponse
    {
        return $this->write('link', $intent);
    }

    /** @return CrmResponse<ReceiptResult> */
    public function unlinkServiceTicketTask(OriginalIntent $intent): CrmResponse
    {
        return $this->write('unlink', $intent);
    }

    /** @return CrmResponse<TasksResult> */
    public function listServiceTicketTasks(string $ticketId): CrmResponse
    {
        return $this->read('tasks', $ticketId, 'listServiceTicketTasks', TasksResult::class);
    }

    /** @return CrmResponse<BillableTimeResult> */
    public function getServiceBillableTime(string $ticketId, ?PageParameters $page = null): CrmResponse
    {
        return $this->read('billable', $ticketId, 'getServiceBillableTime', BillableTimeResult::class, ($page ?? new PageParameters)->query());
    }

    /** @return CrmResponse<TimesheetResult> */
    public function getServiceTimesheet(string $ticketId, ?PageParameters $page = null): CrmResponse
    {
        return $this->read('timesheet', $ticketId, 'getServiceTimesheet', TimesheetResult::class, ($page ?? new PageParameters)->query());
    }

    /** @return CrmResponse<PreviewResult> */
    public function previewServiceTimeInvoice(string $ticketId, PreviewRequest $request): CrmResponse
    {
        $wire = $request->toJson();
        WireCodec::preview($wire);
        $response = $this->transport->requestTaskTimeBillingJson($this->paths->target('preview', $ticketId),
            'previewServiceTimeInvoice', PreviewResult::class, $wire, null, $this->headers);
        self::ticket($response, $ticketId);

        return $response;
    }

    /** Read only; supply the known original effect and binding, not an original wire or new key.
     * @return CrmResponse<ReceiptResult> */
    public function getOriginalServiceTicketTaskLinkReceipt(string $ticketId, RecoveryRequest $request): CrmResponse
    {
        return $this->original('link', $ticketId, $request);
    }

    /** @return CrmResponse<ReceiptResult> */
    public function getOriginalServiceTicketTaskUnlinkReceipt(string $ticketId, RecoveryRequest $request): CrmResponse
    {
        return $this->original('unlink', $ticketId, $request);
    }

    /** @return CrmResponse<ReceiptResult> */
    private function write(string $action, OriginalIntent $intent): CrmResponse
    {
        if ($intent->action !== $action) {
            throw new \InvalidArgumentException('Use the matching immutable Task time intent.');
        }
        try {
            $response = $this->transport->requestTaskTimeBillingJson($this->paths->target($action, $intent->ticketId),
                $action === 'link' ? 'linkServiceTicketTask' : 'unlinkServiceTicketTask', ReceiptResult::class,
                $intent->originalWire, $intent->originalKey, $this->headers);
            self::receipt($response, $intent->ticketId, $intent->taskId, $action, $intent->bindingId);

            return $response;
        } catch (\Throwable $failure) {
            if (self::ambiguous($failure)) {
                throw new UnconfirmedWriteException($intent, $failure);
            }
            throw $failure;
        }
    }

    /** @return CrmResponse<ReceiptResult> */
    private function original(string $action, string $ticketId, RecoveryRequest $request): CrmResponse
    {
        if ($request->action !== $action) {
            throw new \InvalidArgumentException('Use the matching known original receipt tuple.');
        }
        $response = $this->transport->requestTaskTimeBillingJson($this->paths->target($action === 'link' ? 'originalLink' : 'originalUnlink', $ticketId),
            $action === 'link' ? 'getOriginalServiceTicketTaskLinkReceipt' : 'getOriginalServiceTicketTaskUnlinkReceipt',
            ReceiptResult::class, $request->toJson(), null, $this->headers);
        self::receipt($response, $ticketId, $request->taskId, $action, $request->bindingId, $request->effectId);

        return $response;
    }

    /** @template T of WireModel
     * @param class-string<T> $type
     * @param array<string,scalar|null> $query
     * @return CrmResponse<T> */
    private function read(string $route, string $ticketId, string $operation, string $type, array $query = []): CrmResponse
    {
        $response = $this->transport->request('GET', $this->paths->target($route, $ticketId), $operation, $type, $query, null, null, false, $this->headers);
        self::ticket($response, $ticketId);

        return $response;
    }

    /** @param CrmResponse<WireModel> $response */
    private static function ticket(CrmResponse $response, string $ticketId): void
    {
        $body = $response->body;
        if ((! $body instanceof ReceiptResult && ! $body instanceof TasksResult && ! $body instanceof TimesheetResult
            && ! $body instanceof BillableTimeResult && ! $body instanceof PreviewResult)
            || $response->statusCode() !== 200 || strcasecmp($body->data->ticketId, $ticketId) !== 0) {
            throw new \UnexpectedValueException('Native Task time requires HTTP 200 and the same ticket identity.');
        }
    }

    /** @param CrmResponse<ReceiptResult> $response */
    private static function receipt(CrmResponse $response, string $ticketId, string $taskId, string $action, ?string $bindingId = null, ?string $effectId = null): void
    {
        self::ticket($response, $ticketId);
        $data = $response->body->data;
        if ($data->operation !== 'customer_service.task_time_'.$action || strcasecmp($data->result->binding->taskId, $taskId) !== 0
            || ($bindingId !== null && strcasecmp($data->result->binding->id, $bindingId) !== 0)
            || ($effectId !== null && strcasecmp($data->effectId, $effectId) !== 0)) {
            throw new \UnexpectedValueException('Native receipt differs from the original Task time tuple.');
        }
    }

    private static function ambiguous(\Throwable $failure): bool
    {
        if ($failure instanceof CrmApiException) {
            $status = $failure->statusCode;

            return $failure->unconfirmed || $status === null || $status < 400 || $status >= 500;
        }

        if ($failure instanceof ErrorThrowable || $failure instanceof APIException) {
            $status = $failure instanceof ErrorThrowable ? ($failure->container->rawResponse?->getStatusCode() ?? $failure->getCode()) : $failure->statusCode;

            return $status >= 500 || ($status >= 200 && $status < 300);
        }

        return true;
    }
}
