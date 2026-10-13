<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService;

use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

/** Sealed original POST path/body/CAS/key/context. No private owner receipt or file identity is fabricated. */
final readonly class TicketWriteIntent
{
    private function __construct(public TicketOperation $operation, public string $path, public string $ticketId, public int $expectedVersion,
        public string $bodyJson, public string $idempotencyKey, public string $contextFingerprint, public ?string $factuareaVersion, public ?string $xActiveProfile,
        public bool $headersFrozen = false, public ?string $requestAuthFingerprint = null)
    {
    }

    public static function create(TicketOperation $operation, string $ticketId, Request\MoveToStageRequest|Request\AttachDocumentRequest|Request\DetachDocumentRequest $body,
        string $key, string $contextFingerprint, ?string $version = null, ?string $profile = null): self
    {
        $expected = match ($operation) {
            TicketOperation::MoveToStage => Request\MoveToStageRequest::class,
            TicketOperation::AttachDocument => Request\AttachDocumentRequest::class,
            TicketOperation::DetachDocument => Request\DetachDocumentRequest::class,
        };
        if ($body::class !== $expected) {
            throw new InvalidArgumentException('The ticket operation requires its original native request type.');
        }
        $ticketId = strtolower(Wire::uuid($ticketId));
        if ($profile !== null) {
            Wire::uuid($profile);
        }

        return new self($operation, '/crm/service-tickets/'.$ticketId.$operation->suffix(), $ticketId, $body->expectedVersion,
            json_encode((object) $body->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION),
            Wire::key($key), $contextFingerprint, $version, $profile);
    }
    public function freezeHeaders(?string $version, ?string $profile, string $auth): self
    {
        if ($this->headersFrozen) {
            return $this;
        }

        return new self($this->operation, $this->path, $this->ticketId, $this->expectedVersion, $this->bodyJson, $this->idempotencyKey,
            $this->contextFingerprint, $version, $profile, true, $auth);
    }
}
