<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;
use Factuarea\Sdk\Custom\Crm\Documents\Request;
use InvalidArgumentException;

/** Sealed original wire bytes/CAS/key. Headers contain fingerprints, never a copied secret. */
final readonly class DocumentWriteIntent
{
    private function __construct(public DocumentOperation $operation, public string $method, public string $path, public string $bodyJson, public string $idempotencyKey,
        public string $contextFingerprint, public ?string $targetId, public ?string $opportunityId, public ?string $documentType, public ?string $documentId,
        public ?int $expectedVersion, public ?bool $isPrimary, public ?string $factuareaVersion, public ?string $xActiveProfile,
        public bool $headersFrozen = false, public ?string $requestAuthFingerprint = null)
    {
    }
    public static function create(DocumentOperation $operation, Request\UploadDocumentRequest|Request\ReplaceDocumentVersionRequest|Request\ShareDocumentRequest|Request\RevokeDocumentShareRequest|Request\CasRequest|Request\LinkOpportunityDocumentRequest|Request\CreateOpportunityQuoteRequest $body,
        string $key, string $contextFingerprint, ?string $targetId = null, ?string $opportunityId = null, ?string $version = null, ?string $profile = null): self
    {
        $type = match ($operation) {
            DocumentOperation::Upload => Request\UploadDocumentRequest::class,DocumentOperation::Replace => Request\ReplaceDocumentVersionRequest::class,DocumentOperation::Share => Request\ShareDocumentRequest::class,DocumentOperation::RevokeShare => Request\RevokeDocumentShareRequest::class,DocumentOperation::Archive,DocumentOperation::Unlink => Request\CasRequest::class,DocumentOperation::CreateQuote => Request\CreateOpportunityQuoteRequest::class,DocumentOperation::Link => Request\LinkOpportunityDocumentRequest::class
        };
        if ($body::class !== $type || ($operation->library() !== ($opportunityId === null)) || ($operation === DocumentOperation::Upload && $targetId !== null)
            || (in_array($operation, [DocumentOperation::Replace, DocumentOperation::Share, DocumentOperation::RevokeShare, DocumentOperation::Archive, DocumentOperation::Unlink], true) && $targetId === null)
            || (in_array($operation, [DocumentOperation::CreateQuote, DocumentOperation::Link], true) && $targetId !== null)) {
            throw new InvalidArgumentException('The native document operation requires its original body and identities.');
        }
        if ($targetId !== null) {
            $targetId = strtolower(Wire::uuid($targetId));
        }
        if ($opportunityId !== null) {
            $opportunityId = strtolower(Wire::uuid($opportunityId));
        }
        if ($profile !== null) {
            Wire::uuid($profile);
        }
        $path = match ($operation) {
            DocumentOperation::Upload => '/crm/documents',DocumentOperation::Replace => '/crm/documents/'.$targetId.'/versions',DocumentOperation::Share => '/crm/documents/'.$targetId.'/shares',DocumentOperation::RevokeShare => '/crm/documents/'.$targetId.'/shares/revoke',DocumentOperation::Archive => '/crm/documents/'.$targetId.'/archive',DocumentOperation::CreateQuote => '/crm/opportunitydocuments/'.$opportunityId.'/quotes',DocumentOperation::Link => '/crm/opportunitydocuments/'.$opportunityId.'/documents',DocumentOperation::Unlink => '/crm/opportunitydocuments/'.$opportunityId.'/documents/'.$targetId
        };
        $json = json_encode((object) $body->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
        if (strlen($json) > 262144) {
            throw new InvalidArgumentException('The document mutation exceeds its native payload limit.');
        }

        return new self($operation, $operation === DocumentOperation::Unlink ? 'DELETE' : 'POST', $path, $json, Wire::key($key), $contextFingerprint, $targetId, $opportunityId,
            $body instanceof Request\LinkOpportunityDocumentRequest ? $body->documentType : ($operation === DocumentOperation::CreateQuote ? 'quote' : null),
            $body instanceof Request\LinkOpportunityDocumentRequest ? $body->documentId : null, property_exists($body, 'expectedVersion') ? $body->expectedVersion : null,
            $body instanceof Request\LinkOpportunityDocumentRequest ? $body->isPrimary : null, $version, $profile);
    }
    public function freezeHeaders(?string $version, ?string $profile, string $auth): self
    {
        if ($this->headersFrozen) {
            return $this;
        }

        return new self($this->operation, $this->method, $this->path, $this->bodyJson, $this->idempotencyKey, $this->contextFingerprint, $this->targetId, $this->opportunityId, $this->documentType, $this->documentId, $this->expectedVersion, $this->isPrimary, $version, $profile, true, $auth);
    }
}
