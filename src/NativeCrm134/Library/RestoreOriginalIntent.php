<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Library;

use Factuarea\Sdk\Custom\Crm\Native139\Documents\RestoreIntent;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\NativeCrm134\Model;

/** Readback of a caller-retained restore; this intent has no dispatch method. */
final readonly class RestoreOriginalIntent
{
    public int $expectedVersion;

    private function __construct(public string $document, public string $originalBody, public string $originalBodySha256,
        public string $idempotencyKey, public string|Omitted $factuareaVersion, public string|Omitted $xActiveProfile)
    {
        $values = json_decode($originalBody, false, 512, JSON_THROW_ON_ERROR);
        if (! $values instanceof \stdClass || ! hash_equals($originalBodySha256, hash('sha256', $originalBody))) {
            throw new \InvalidArgumentException('Retain the exact original restore body, its SHA-256 and original key.');
        }
        $body = Model\CrmRestoreCommercialDocumentParametersBody::fromArray((array) $values);
        new Model\CrmRestoreCommercialDocumentParameters($document, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
        $this->expectedVersion = $body->expectedVersion;
        $this->request();
    }

    public static function restore(string $document, string $originalBody, string $originalBodySha256, string $idempotencyKey,
        string|Omitted $factuareaVersion = Omitted::Value, string|Omitted $xActiveProfile = Omitted::Value): self
    {
        return new self($document, $originalBody, $originalBodySha256, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }

    public static function fromNative143(RestoreIntent $intent): self
    {
        return self::restore($intent->document, $intent->originalBody, $intent->originalBodySha256, $intent->idempotencyKey);
    }

    public function request(): Model\CrmGetCommercialDocumentRestoreOriginalParameters
    {
        return new Model\CrmGetCommercialDocumentRestoreOriginalParameters($this->document, $this->expectedVersion,
            $this->idempotencyKey, $this->factuareaVersion, $this->xActiveProfile);
    }
}
