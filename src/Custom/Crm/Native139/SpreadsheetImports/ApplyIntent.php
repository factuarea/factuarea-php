<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports;

use Factuarea\Sdk\Custom\Crm\Native139\Model;

/** Snapshot of the explicitly confirmed native body. Restored bytes authorize only Original reads. */
final readonly class ApplyIntent
{
    private function __construct(public string $run, public string $originalBody, public string $originalBodySha256,
        public string $idempotencyKey, private bool $readbackOnly)
    {
        new Model\PublicApiV1CrmSpreadsheetImportsApplyParameters($run, $this->body(), $idempotencyKey);
        $this->original();
    }

    public static function reviewed(string $run, Model\PublicApiV1CrmSpreadsheetImportsApplyParametersBody $body, string $idempotencyKey): self
    {
        $bytes = $body->toJson();

        return new self($run, $bytes, hash('sha256', $bytes), $idempotencyKey, false);
    }

    public static function restore(string $run, string $originalBody, string $originalBodySha256, string $idempotencyKey): self
    {
        return new self($run, $originalBody, $originalBodySha256, $idempotencyKey, true);
    }

    public function isReadbackOnly(): bool
    {
        return $this->readbackOnly;
    }

    public function original(): ApplyOriginalIntent
    {
        return ApplyOriginalIntent::restore($this->run, $this->originalBody, $this->originalBodySha256, $this->idempotencyKey);
    }

    public function request(): Model\PublicApiV1CrmSpreadsheetImportsApplyParameters
    {
        if ($this->readbackOnly) {
            throw new \LogicException('A restored Apply intent can only read its Original.');
        }
        $body = $this->body();
        if (! hash_equals($this->originalBodySha256, hash('sha256', $body->toJson()))) {
            throw new \LogicException('The reviewed Apply body must retain its exact wire bytes.');
        }

        return new Model\PublicApiV1CrmSpreadsheetImportsApplyParameters($this->run, $body, $this->idempotencyKey);
    }

    private function body(): Model\PublicApiV1CrmSpreadsheetImportsApplyParametersBody
    {
        $values = json_decode($this->originalBody, false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \InvalidArgumentException('A retained native Apply body must be an object.');
        }

        return Model\PublicApiV1CrmSpreadsheetImportsApplyParametersBody::fromArray((array) $values);
    }
}
