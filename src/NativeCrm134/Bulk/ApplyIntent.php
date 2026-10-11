<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Bulk;

use Factuarea\Sdk\NativeCrm134\Model;

/** Retains the exact native action, ordered selection, payload, CAS and original key. */
final readonly class ApplyIntent
{
    private function __construct(public string $originalBody, public string $originalBodySha256,
        public string $idempotencyKey, private bool $readbackOnly)
    {
        $this->parsedRequest();
        if (! hash_equals($originalBodySha256, hash('sha256', $originalBody))) {
            throw new \InvalidArgumentException('Retain the exact reviewed bulk body bytes and their SHA-256.');
        }
    }

    public static function reviewed(Model\ApplyServiceTicketBulkActionParameters $request): self
    {
        $request->validate();
        $bytes = $request->body->toJson();

        return new self($bytes, hash('sha256', $bytes), $request->idempotencyKey, false);
    }

    public static function restore(string $originalBody, string $originalBodySha256, string $idempotencyKey): self
    {
        return new self($originalBody, $originalBodySha256, $idempotencyKey, true);
    }

    public function isReadbackOnly(): bool
    {
        return $this->readbackOnly;
    }

    public function request(): Model\ApplyServiceTicketBulkActionParameters
    {
        if ($this->readbackOnly) {
            throw new \LogicException('A restored bulk intent only reads each native Original.');
        }
        $request = $this->parsedRequest();
        if (! hash_equals($this->originalBodySha256, hash('sha256', $request->body->toJson()))) {
            throw new \LogicException('The reviewed bulk body must retain its exact wire bytes.');
        }

        return $request;
    }

    /** @return list<Model\GetOriginalServiceTicketBulkItemParameters> */
    public function originalRequests(): array
    {
        $body = $this->parsedRequest()->body;
        $requests = [];
        foreach ($body->items as $item) {
            $selection = ['ticket' => $item->id, 'action' => $body->action,
                'expected_version' => $item->expectedVersion, 'idempotency_key' => $this->idempotencyKey];
            $payload = match ($body->action) {
                'assign' => ['assignee_id' => $body->assigneeId ?? 'null'],
                'priority' => ['priority' => $body->priority],
                'tags' => ['tag_ids' => json_encode($body->tagIds, JSON_THROW_ON_ERROR)],
                'status' => ['pipeline_id' => $body->pipelineId, 'stage_id' => $body->stageId],
            };
            $requests[] = Model\GetOriginalServiceTicketBulkItemParameters::fromArray($selection + $payload);
        }

        return $requests;
    }

    private function parsedRequest(): Model\ApplyServiceTicketBulkActionParameters
    {
        $values = json_decode($this->originalBody, false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \InvalidArgumentException('A retained bulk body must be an object.');
        }

        return Model\ApplyServiceTicketBulkActionParameters::fromArray([
            'body' => $values, 'idempotency_key' => $this->idempotencyKey,
        ]);
    }
}
