<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports;

use Factuarea\Sdk\Custom\Crm\Native139\Model\PublicApiV1CrmSpreadsheetImportsApplyOriginalParameters;

/** Caller-retained original bytes and identity. Recovery can only read the SAME Original, never submit a replacement. */
final readonly class ApplyOriginalIntent
{
    private function __construct(public string $run, public string $originalBody, public string $originalBodySha256, public string $idempotencyKey)
    {
        new PublicApiV1CrmSpreadsheetImportsApplyOriginalParameters($run, $originalBodySha256, $idempotencyKey);
        if (preg_match('/^[\x20-\x7e]{1,255}$/D', $idempotencyKey) !== 1
            || ! hash_equals($originalBodySha256, hash('sha256', $originalBody))) {
            throw new \InvalidArgumentException('Retain the exact original import bytes, their SHA-256 and original printable key.');
        }
    }

    public static function restore(string $run, string $originalBody, string $originalBodySha256, string $idempotencyKey): self
    {
        return new self($run, $originalBody, $originalBodySha256, $idempotencyKey);
    }
}
