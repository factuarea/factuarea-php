<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\Schema;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;

/** Native HTTP71 operations sharing the configured SDK security and hooks. Required paths activate no capability. */
final readonly class VerticalPackClient
{
    /** @param array<string,string> $headers */
    public function __construct(private Transport $transport, private RequiredPaths $paths, private array $headers = [])
    {
        foreach ($headers as $name => $value) {
            if (!in_array($name, ['X-Active-Profile', 'Factuarea-Version'], true)) {
                throw new \InvalidArgumentException('Only the native profile/version header overrides are supported.');
            }
            self::header($value);
        }
    }

    public function withHeaders(?string $xActiveProfile = null, ?string $factuareaVersion = null): self
    {
        $headers = $this->headers;
        foreach (['X-Active-Profile' => $xActiveProfile, 'Factuarea-Version' => $factuareaVersion] as $name => $value) {
            if ($value !== null) {
                self::header($value);
                $headers[$name] = $value;
            }
        }

        return new self($this->transport, $this->paths, $headers);
    }

    /** The native four-template catalogue currently has no non-null cursor.
     * @return CrmResponse<PackPageResult> */
    public function listCrmPacks(?string $sector = null, ?string $cursor = null): CrmResponse
    {
        if ($sector !== null) {
            Schema::validate(Schemas::SECTOR, $sector);
        }
        if ($cursor !== null) {
            throw new \InvalidArgumentException('The native catalogue has no further cursor.');
        }
        $response = $this->transport->request('GET', $this->paths->list, 'crmConfigurationListPacks', PackPageResult::class,
            $sector === null ? [] : ['sector' => $sector], null, null, false, $this->headers);
        self::status($response->statusCode());
        foreach ($response->body->data->items as $pack) {
            if ($sector !== null && $pack->sector !== $sector) {
                throw new \UnexpectedValueException('The catalogue differs from the requested native sector.');
            }
        }

        return $response;
    }

    /** @return CrmResponse<PlanResult> */
    public function previewCrmPack(string $packId, PreviewRequest $request): CrmResponse
    {
        $response = $this->transport->requestVerticalPackJson($this->paths->target('preview', $packId),
            'crmConfigurationPreviewPack', PlanResult::class, $request->toJson(), null, $this->headers);
        $plan = $response->body->data;
        if ($plan->packId !== $packId || $plan->packVersion !== $request->packVersion || $plan->demoSelected !== $request->demoSelected) {
            throw new \UnexpectedValueException('The emitted native plan differs from the selected pack/version/demo tuple.');
        }

        return $response;
    }

    /** The original encrypted token stays in the request body; server TTL is never extended.
     * @return CrmResponse<PlanResult> */
    public function getCrmPackPlan(Plan $original): CrmResponse
    {
        $response = $this->transport->requestVerticalPackJson($this->paths->target('plan', $original->id),
            'crmConfigurationGetPackPlan', PlanResult::class, (new PlanReadRequest($original->planToken))->toJson(), null, $this->headers);
        if ($response->body->data->toJson() !== $original->toJson()) {
            throw new \UnexpectedValueException('A plan read must retain the exact original native plan and token.');
        }

        return $response;
    }

    /** @return CrmResponse<ConflictsResult> */
    public function getCrmPackConflicts(Plan $original): CrmResponse
    {
        $response = $this->transport->requestVerticalPackJson($this->paths->target('conflicts', $original->id),
            'crmConfigurationGetPackConflicts', ConflictsResult::class, (new PlanReadRequest($original->planToken))->toJson(), null, $this->headers);
        $expected = array_values(array_filter($original->items, static fn (PlanItem $item): bool => $item->decision === 'conflict'));
        if (!hash_equals($original->previewHash, $response->body->data->previewHash)
            || array_map(static fn (PlanItem $item): string => $item->toJson(), $expected)
                !== array_map(static fn (PlanItem $item): string => $item->toJson(), $response->body->data->items)) {
            throw new \UnexpectedValueException('Conflict choices must belong to the same original native plan.');
        }

        return $response;
    }

    /** @return CrmResponse<ReceiptResult> */
    public function applyCrmPack(OriginalIntent $intent): CrmResponse
    {
        return $this->write('apply', $intent);
    }

    /** @return CrmResponse<ReceiptResult> */
    public function undoCrmPackInstallation(OriginalIntent $intent): CrmResponse
    {
        return $this->write('undo', $intent);
    }

    /** Read only. Absence stays null, with no reserve, dispatch, new key or current-head substitution.
     * @return CrmResponse<OriginalReceipt> */
    public function getOriginalCrmPackReceipt(OriginalIntent $intent): CrmResponse
    {
        $response = $this->transport->requestVerticalPackJson($this->paths->recover, 'crmConfigurationGetOriginalPackReceipt',
            OriginalReceipt::class, RecoveryRequest::fromIntent($intent)->toJson(), null, $this->headers);
        if ($response->body->receipt !== null) {
            self::receipt($response->body->receipt, $intent);
        }

        return $response;
    }

    /** @return CrmResponse<ReceiptResult> */
    private function write(string $operation, OriginalIntent $intent): CrmResponse
    {
        if ($intent->operation !== $operation) {
            throw new \InvalidArgumentException('Use the matching immutable apply/undo intent.');
        }
        try {
            $response = $this->transport->requestVerticalPackJson($this->paths->target($operation, $intent->targetId),
                $operation === 'apply' ? 'crmConfigurationApplyPack' : 'crmConfigurationUndoPackInstallation',
                ReceiptResult::class, $intent->originalWire, $intent->originalKey, $this->headers);
            self::receipt($response->body->data, $intent);

            return $response;
        } catch (\Throwable $failure) {
            if (self::ambiguous($failure)) {
                throw new UnconfirmedWriteException($intent, $failure);
            }
            throw $failure;
        }
    }

    private static function receipt(Receipt $receipt, OriginalIntent $intent): void
    {
        if ($receipt->operation !== $intent->operation || $receipt->targetId !== $intent->targetId) {
            throw new \UnexpectedValueException('The receipt differs from the original native operation/target.');
        }
    }

    private static function header(string $value): void
    {
        if ($value === '' || preg_match('/[\x00-\x1f\x7f]/', $value) === 1) {
            throw new \InvalidArgumentException('Invalid native header value.');
        }
    }
    private static function status(int $status): void
    {
        if ($status !== 200) {
            throw new \UnexpectedValueException('Native VerticalPacks requires HTTP 200.');
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

            return $status < 400 || $status >= 500;
        }

        return true;
    }
}
