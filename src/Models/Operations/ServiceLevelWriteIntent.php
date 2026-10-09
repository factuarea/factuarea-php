<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Operations;

use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Utils\JSON;
use InvalidArgumentException;

/** Immutable original JSON, CAS and key. Recovery never substitutes a fresh identity or version. */
final readonly class ServiceLevelWriteIntent
{
    private function __construct(
        public string $operation,
        public ?string $ticket,
        public string $bodyJson,
        public string $idempotencyKey,
        public ?string $factuareaVersion,
        public ?string $xActiveProfile,
        public string $contextFingerprint,
        public ?string $expectedResultId,
        public ?string $requestAuthFingerprint = null,
        public bool $headersFrozen = false,
    ) {
    }

    public static function create(
        string $operation,
        ?string $ticket,
        Components\ConfigureServiceSlaRequest|Components\PauseServiceSlaRequest|Components\ResumeServiceSlaRequest|Components\UpdateServiceCalendarRequest $body,
        string $idempotencyKey,
        ?string $factuareaVersion,
        ?string $xActiveProfile,
        string $contextFingerprint,
    ): self {
        $expectedClass = match ($operation) {
            'configureServiceSla' => Components\ConfigureServiceSlaRequest::class,
            'pauseServiceSla' => Components\PauseServiceSlaRequest::class,
            'resumeServiceSla' => Components\ResumeServiceSlaRequest::class,
            'updateServiceCalendar' => Components\UpdateServiceCalendarRequest::class,
            default => throw new InvalidArgumentException('Operación SLA desconocida.'),
        };
        if ($body::class !== $expectedClass || (($operation === 'updateServiceCalendar') !== ($ticket === null))) {
            throw new InvalidArgumentException('La intención SLA requiere su cuerpo y ticket originales.');
        }
        if (strlen($idempotencyKey) > 255 || preg_match('/^[\x20-\x7e]+$/D', $idempotencyKey) !== 1) {
            throw new InvalidArgumentException('La clave de idempotencia debe contener entre 1 y 255 caracteres ASCII.');
        }
        $resultId = $body instanceof Components\UpdateServiceCalendarRequest ? $body->id : $body->cycleId;

        return new self($operation, $ticket, JSON::createSerializer()->serialize($body, 'json'), $idempotencyKey,
            $factuareaVersion, $xActiveProfile, $contextFingerprint, $resultId);
    }

    /** Snapshot the effective native version/profile headers after the initial before-request hooks. */
    public function freezeHeaders(?string $version, ?string $profile, string $requestAuthFingerprint): self
    {
        if ($this->headersFrozen) {
            return $this;
        }

        return new self($this->operation, $this->ticket, $this->bodyJson, $this->idempotencyKey,
            $version, $profile, $this->contextFingerprint, $this->expectedResultId, $requestAuthFingerprint, true);
    }
}
