<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\Documents;

use Factuarea\Sdk\Custom\Crm\Native139\Model;

/** Explicit native confirmation and revision, frozen before the first restore attempt. */
final readonly class RestoreIntent
{
    private function __construct(public string $document, public string $originalBody, public string $originalBodySha256,
        public string $idempotencyKey, private bool $readbackOnly)
    {
        new Model\CrmRestoreCommercialDocumentParameters($document, $this->body(), $idempotencyKey);
        if (! hash_equals($originalBodySha256, hash('sha256', $originalBody))
            || preg_match('/^[\x20-\x7e]{1,255}$/D', $idempotencyKey) !== 1) {
            throw new \InvalidArgumentException('Retain the exact reviewed restore bytes, their hash and printable original key.');
        }
    }

    public static function reviewed(string $document, Model\CrmRestoreCommercialDocumentParametersBody $body, string $idempotencyKey): self
    {
        $bytes = $body->toJson();

        return new self($document, $bytes, hash('sha256', $bytes), $idempotencyKey, false);
    }

    public static function restore(string $document, string $originalBody, string $originalBodySha256, string $idempotencyKey): self
    {
        return new self($document, $originalBody, $originalBodySha256, $idempotencyKey, true);
    }

    public function isReadbackOnly(): bool
    {
        return $this->readbackOnly;
    }

    public function request(): Model\CrmRestoreCommercialDocumentParameters
    {
        if ($this->readbackOnly) {
            throw new \LogicException('Source143 has no separate restore Original GET; a retained restore cannot be resent.');
        }
        $body = $this->body();
        if (! hash_equals($this->originalBodySha256, hash('sha256', $body->toJson()))) {
            throw new \LogicException('The reviewed restore body must retain its exact wire bytes.');
        }

        return new Model\CrmRestoreCommercialDocumentParameters($this->document, $body, $this->idempotencyKey);
    }

    private function body(): Model\CrmRestoreCommercialDocumentParametersBody
    {
        $values = json_decode($this->originalBody, false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \InvalidArgumentException('A retained native restore body must be an object.');
        }

        return Model\CrmRestoreCommercialDocumentParametersBody::fromArray((array) $values);
    }
}
