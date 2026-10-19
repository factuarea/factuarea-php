<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\Schema;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;

/** Handwritten native Sequence resource; routes and canonical raw/void transport are REQUIRED. */
final readonly class SequenceClient
{
    /** @param array<string,string> $headers Only the existing version/profile headers. */
    public function __construct(private OriginalJsonTransportInterface $transport,
        private RequiredPaths $paths, private array $headers = [])
    {
        foreach ($headers as $name => $value) {
            if (! in_array($name, ['Factuarea-Version', 'X-Active-Profile'], true) || $value === '' || preg_match('/[\r\n]/', $value) === 1) {
                throw new \InvalidArgumentException('Use the canonical configured authentication and version/profile headers.');
            }
        }
    }

    /** @return CrmResponse<ListResult> */
    public function list(string $filtersJson, int $limit, ?string $cursor = null): CrmResponse
    {
        $filters = WireCodec::decode($filtersJson);
        $resource = $filters->resource ?? null;
        $allowed = $resource === 'schedules' ? ['resource', 'state', 'owner_id', 'team_id']
            : ['resource', 'state', 'owner_id', 'team_id', 'sequence_id', 'definition_version', 'stop_reason'];
        $properties = ['resource' => ['type' => 'string', 'enum' => ['schedules', 'enrolments']],
            'state' => $resource === 'schedules' ? Schemas::SCHEDULE_STATE : Schemas::ENROL_STATE,
            'owner_id' => Schemas::OWNER, 'team_id' => Schemas::SCHEDULE['properties']['team_id'],
            'sequence_id' => Schemas::ID, 'definition_version' => Schemas::VERSION, 'stop_reason' => Schemas::STOP];
        Schema::validate(['type' => 'object', 'additionalProperties' => false, 'required' => ['resource'],
            'properties' => array_intersect_key($properties, array_flip($allowed))], $filters);
        if ($limit < 1 || $limit > 100 || strlen($filtersJson) > 8192 || ($cursor !== null &&
            ($cursor === '' || strlen($cursor) > 8192 || preg_match('/^[A-Za-z0-9+\/=_-]+$/D', $cursor) !== 1))) {
            throw new \InvalidArgumentException('Supply the native opaque cursor and list limit.');
        }
        $query = ['filters' => $filtersJson, 'limit' => $limit];
        if ($cursor !== null) {
            $query['cursor'] = $cursor;
        }

        return $this->transport->request('GET', $this->paths->list, 'automation.crm_sequence_list', ListResult::class,
            $query, null, null, false, $this->headers);
    }

    /** @return CrmResponse<PreviewResult> */
    public function preview(string $sequenceId, string $subjectType, string $subjectId, string $startAt): CrmResponse
    {
        WireCodec::subject($subjectType);
        WireCodec::id($subjectId);
        if (strlen($startAt) > 40 || preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,6})?(?:Z|[+-]\d{2}:\d{2})$/D', $startAt) !== 1) {
            throw new \InvalidArgumentException('Supply the explicit native ISO offset for preview.');
        }
        new \DateTimeImmutable($startAt);
        $dateErrors = \DateTimeImmutable::getLastErrors();
        if ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) {
            throw new \InvalidArgumentException('Preview requires a valid calendar instant.');
        }

        return $this->transport->request('GET', $this->paths->target('preview', $sequenceId), 'automation.crm_sequence_preview',
            PreviewResult::class, ['subject_type' => $subjectType, 'subject_id' => $subjectId, 'start_at' => $startAt], null, null, false, $this->headers);
    }

    /** @return CrmResponse<SaveResult> */
    public function save(OriginalIntent $intent): CrmResponse
    {
        if ($intent->operation !== 'save') {
            throw new \InvalidArgumentException('Use the immutable save intent.');
        }
        try {
            $response = $this->transport->requestOriginalJson($intent->targetId === null ? $this->paths->create
                : $this->paths->target('save', $intent->targetId), 'automation.crm_sequence_save', SaveResult::class,
                $intent->originalWire, $intent->originalKey, $this->headers);
            if ($intent->targetId !== null && strcasecmp($response->body->data->id, $intent->targetId) !== 0) {
                throw new \UnexpectedValueException('Original save identity does not match the intent.');
            }

            return $response;
        } catch (\Throwable $failure) {
            if (self::ambiguous($failure)) {
                throw new UnconfirmedWriteException($intent, $failure);
            }
            throw $failure;
        }
    }

    /** @return CrmResponse<EnrolResult> */
    public function enrol(OriginalIntent $intent): CrmResponse
    {
        if ($intent->operation !== 'enrol' || $intent->targetId === null) {
            throw new \InvalidArgumentException('Use the immutable enrol intent.');
        }
        try {
            $response = $this->transport->requestOriginalJson($this->paths->target('enrol', $intent->targetId),
                'automation.crm_sequence_enrol', EnrolResult::class, $intent->originalWire, $intent->originalKey, $this->headers);
            $r = $response->body->data;
            if (strcasecmp($r->sequenceId, $intent->targetId) !== 0 || $r->subjectType !== $intent->subjectType
                || $intent->subjectId === null || strcasecmp($r->subjectId, $intent->subjectId) !== 0
                || $r->definitionVersion !== $intent->expectedVersion) {
                throw new \UnexpectedValueException('Original enrolment does not match the intent.');
            }

            return $response;
        } catch (\Throwable $failure) {
            if (self::ambiguous($failure)) {
                throw new UnconfirmedWriteException($intent, $failure);
            }
            throw $failure;
        }
    }

    /** Read only: sends no original wire, reserves no identity and never repeats the mutation.
     * @return CrmResponse<SaveResult> */
    public function originalSave(string $originalKey): CrmResponse
    {
        WireCodec::key($originalKey);

        return $this->transport->request('GET', $this->paths->originalSave, 'automation.crm_sequence_save_original',
            SaveResult::class, [], null, $originalKey, false, $this->headers);
    }

    /** @return CrmResponse<EnrolResult> */
    public function originalEnrol(string $originalKey): CrmResponse
    {
        WireCodec::key($originalKey);

        return $this->transport->request('GET', $this->paths->originalEnrol, 'automation.crm_sequence_enrol_original',
            EnrolResult::class, [], null, $originalKey, false, $this->headers);
    }

    public function pause(string $enrolmentId, int $expectedVersion): void
    {
        $this->control('pause', $enrolmentId, $expectedVersion);
    }

    public function resume(string $enrolmentId, int $expectedVersion): void
    {
        $this->control('resume', $enrolmentId, $expectedVersion);
    }

    public function cancel(string $enrolmentId, int $expectedVersion): void
    {
        $this->control('cancel', $enrolmentId, $expectedVersion);
    }

    private function control(string $action, string $enrolmentId, int $expectedVersion): void
    {
        WireCodec::id($enrolmentId);
        WireCodec::version($expectedVersion);
        $values = ['expected_version' => $expectedVersion];
        if ($action === 'pause') {
            $values['reason'] = 'manual';
        }
        try {
            $response = $this->transport->requestControl($this->paths->target($action, $enrolmentId),
                'automation.crm_sequence_'.$action, json_encode($values, JSON_THROW_ON_ERROR), $this->headers);
            if ($response->getStatusCode() !== 204 || (string) $response->getBody() !== '') {
                throw new \UnexpectedValueException('Native sequence control must return empty 204.');
            }
        } catch (\Throwable $failure) {
            if (self::ambiguous($failure)) {
                throw new UnconfirmedControlException($action, $enrolmentId, $expectedVersion, $failure);
            }
            throw $failure;
        }
    }

    private static function ambiguous(\Throwable $failure): bool
    {
        if ($failure instanceof CrmApiException) {
            $status = $failure->statusCode;

            return $failure->unconfirmed || $status === null || $status < 400 || $status >= 500;
        }

        if ($failure instanceof ErrorThrowable || $failure instanceof APIException) {
            // The generated ErrorThrowable code is -1; its canonical raw response carries HTTP status.
            $status = $failure instanceof ErrorThrowable ? ($failure->container->rawResponse?->getStatusCode() ?? $failure->getCode())
                : $failure->statusCode;

            return $status >= 500 || ($status >= 200 && $status < 300);
        }

        return true;
    }
}
