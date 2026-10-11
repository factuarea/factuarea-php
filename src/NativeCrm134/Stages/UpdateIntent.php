<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Stages;

use Factuarea\Sdk\NativeCrm134\Model;

/** Caller-retained stage configuration and CAS; recovery cannot replace this wire. */
final readonly class UpdateIntent
{
    public string $requestFingerprint;

    private function __construct(public string $stage, public string $originalBody, public string $originalBodySha256,
        public string $idempotencyKey, private bool $readbackOnly)
    {
        $body = $this->body();
        new Model\UpdateServiceStageParameters($stage, $body, $idempotencyKey);
        if (! hash_equals($originalBodySha256, hash('sha256', $originalBody))) {
            throw new \InvalidArgumentException('Retain the exact stage body bytes and their SHA-256.');
        }
        $normalized = ['stage_uuid' => strtolower($stage), 'pipeline_uuid' => strtolower($body->pipelineId),
            'expected_version' => $body->expectedVersion, 'expected_pipeline_version' => $body->expectedPipelineVersion,
            'name' => $body->name, 'mapped_status' => $body->mappedStatus, 'wip_limit' => $body->wipLimit];
        ksort($normalized, SORT_STRING);
        $this->requestFingerprint = hash('sha256', json_encode((object) $normalized,
            JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->originalRequest();
    }

    public static function reviewed(string $stage, Model\UpdateServiceStageParametersBody $body, string $idempotencyKey): self
    {
        $bytes = $body->toJson();

        return new self($stage, $bytes, hash('sha256', $bytes), $idempotencyKey, false);
    }

    public static function restore(string $stage, string $originalBody, string $originalBodySha256, string $idempotencyKey): self
    {
        return new self($stage, $originalBody, $originalBodySha256, $idempotencyKey, true);
    }

    public function isReadbackOnly(): bool
    {
        return $this->readbackOnly;
    }

    public function request(): Model\UpdateServiceStageParameters
    {
        if ($this->readbackOnly) {
            throw new \LogicException('A restored stage intent only reads its native Original.');
        }
        $body = $this->body();
        if (! hash_equals($this->originalBodySha256, hash('sha256', $body->toJson()))) {
            throw new \LogicException('The reviewed stage body must retain its exact wire bytes.');
        }

        return new Model\UpdateServiceStageParameters($this->stage, $body, $this->idempotencyKey);
    }

    public function originalRequest(): Model\GetServiceStageOriginalParameters
    {
        return new Model\GetServiceStageOriginalParameters($this->stage, $this->requestFingerprint, $this->idempotencyKey);
    }

    public function body(): Model\UpdateServiceStageParametersBody
    {
        $values = json_decode($this->originalBody, false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \InvalidArgumentException('A retained stage body must be an object.');
        }

        return Model\UpdateServiceStageParametersBody::fromArray((array) $values);
    }
}
