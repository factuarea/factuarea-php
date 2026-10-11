<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

/** Immutable confirmation snapshot; its hash describes exact SDK request bytes, not a grant. */
final readonly class ExportIntent
{
    public string $originalBody;

    public string $originalBodySha256;

    /** @param array<string,mixed> $filters @param list<string> $columns */
    public function __construct(
        public string $id,
        public EntityType $entityType,
        array $filters,
        array $columns,
        bool $confirmed,
        public string $idempotencyKey,
        private bool $readbackOnly = false,
    ) {
        if (preg_match('/^[\x21-\x7e](?:[\x20-\x7e]{0,253}[\x21-\x7e])?$/D', $idempotencyKey) !== 1) {
            throw new \InvalidArgumentException('Retain a stable printable Idempotency-Key without boundary whitespace, max255.');
        }
        $request = new ExportRequest($id, $entityType->value, Selection::canonicalObject($filters), $columns, $confirmed);
        $this->originalBody = $request->toJson();
        $this->originalBodySha256 = hash('sha256', $this->originalBody);
    }

    /** Restore retained exact bytes after a restart; this intent can only read Original. */
    public static function restore(string $originalBody, string $originalBodySha256, string $idempotencyKey): self
    {
        if (! hash_equals($originalBodySha256, hash('sha256', $originalBody))) {
            throw new \InvalidArgumentException('The retained original export bytes must match their retained hash.');
        }
        $values = json_decode($originalBody, false, 64, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass) {
            throw new \InvalidArgumentException('Retain the original export request object.');
        }
        $request = ExportRequest::fromArray((array) $values);
        $intent = new self($request->id, EntityType::from($request->entityType), (array) $request->filters,
            $request->columns, $request->confirmed, $idempotencyKey, true);
        if ($intent->originalBody !== $originalBody) {
            throw new \InvalidArgumentException('Restore exact SDK original bytes without replacing their serialization.');
        }

        return $intent;
    }

    public function isReadbackOnly(): bool
    {
        return $this->readbackOnly;
    }

    /** Return a fresh DTO so changes to an exposed object can never replace the intent. */
    public function request(): ExportRequest
    {
        return ExportRequest::fromArray((array) json_decode($this->originalBody, false, 64, JSON_THROW_ON_ERROR));
    }
}
