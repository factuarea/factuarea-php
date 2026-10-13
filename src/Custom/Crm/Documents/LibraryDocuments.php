<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;
use Brick\DateTime\LocalDate;
use Factuarea\Sdk\SDKConfiguration;
use Generator;
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\StreamInterface;

/** Current private document-library v1 contract. Company/environment come from the configured credential. */
final readonly class LibraryDocuments
{
    private DocumentTransport $transport;
    public function __construct(SDKConfiguration $configuration)
    {
        $this->transport = new DocumentTransport($configuration);
    }
    /** @return DocumentResponse<Model\LibraryPage> */
    public function listDocuments(int $page = 1, int $perPage = 25, ?string $search = null, ?string $sort = null, ?bool $archived = null, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        Wire::integer($page);
        Wire::integer($perPage);
        if ($perPage > 100) {
            throw new InvalidArgumentException('Document page size cannot exceed 100.');
        }
        $query = ['page' => $page, 'per_page' => $perPage];
        if ($search !== null) {
            $query['search'] = Wire::text($search, 255, true);
        }
        if ($sort !== null) {
            if (!in_array($sort, ['id', 'title', 'version'], true)) {
                throw new InvalidArgumentException('Unsupported document-library sort.');
            }$query['sort'] = $sort;
        }
        if ($archived !== null) {
            $query['archived'] = $archived ? 'true' : 'false';
        }

        return $this->transport->read('crm_documents.list', '/crm/documents', $query, static function (mixed $wire) use ($page, $perPage): Model\LibraryPage {
            $result = Model\LibraryPage::fromWire($wire);
            if ($result->page !== $page || $result->perPage !== $perPage) {
                throw new InvalidArgumentException('The document response belongs to another page.');
            }

            return $result;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** @return Generator<int,Model\LibraryDocument> */
    public function iterateDocuments(int $perPage = 25, ?string $search = null, ?string $sort = null, ?bool $archived = null, int $maxPages = 1000, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Generator
    {
        Wire::integer($maxPages);
        for ($page = 1; $page <= $maxPages; $page++) {
            $result = $this->listDocuments($page, $perPage, $search, $sort, $archived, $factuareaVersion, $xActiveProfile)->data;
            foreach ($result->items as $item) {
                yield $item;
            }
            if ($page * $perPage >= $result->total) {
                return;
            }
            if ($result->items === []) {
                throw new LogicException('The document page has no progress despite its reported total.');
            }
        }
        throw new LogicException('Document iteration reached its explicit maximum page count.');
    }
    /** @return DocumentResponse<Model\LibraryDocument> */
    public function getDocument(string $documentId, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        $id = strtolower(Wire::uuid($documentId));

        return $this->transport->read('crm_documents.get', '/crm/documents/'.$id, [], static function (mixed $wire) use ($id): Model\LibraryDocument {
            $result = Model\LibraryDocument::fromWire($wire);
            if (strcasecmp($result->id, $id) !== 0) {
                throw new InvalidArgumentException('The document response belongs to another identity.');
            }

            return $result;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** The existing backend accepts an accredited native producer reference, not a filename or public URL.
     * @return DocumentResponse<Model\LibraryReceipt> */
    public function uploadDocument(Request\UploadDocumentRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->write(DocumentOperation::Upload, $body, $idempotencyKey, null, $factuareaVersion, $xActiveProfile);
    }
    /** @return DocumentResponse<Model\LibraryReceipt> */
    public function replaceVersion(string $documentId, Request\ReplaceDocumentVersionRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->write(DocumentOperation::Replace, $body, $idempotencyKey, $documentId, $factuareaVersion, $xActiveProfile);
    }
    /** @return DocumentResponse<Model\LibraryReceipt> */
    public function shareDocument(string $documentId, Request\ShareDocumentRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->write(DocumentOperation::Share, $body, $idempotencyKey, $documentId, $factuareaVersion, $xActiveProfile);
    }
    /** @return DocumentResponse<Model\LibraryReceipt> */
    public function revokeShare(string $documentId, Request\RevokeDocumentShareRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->write(DocumentOperation::RevokeShare, $body, $idempotencyKey, $documentId, $factuareaVersion, $xActiveProfile);
    }
    /** @return DocumentResponse<Model\LibraryReceipt> */
    public function archiveDocument(string $documentId, Request\CasRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->write(DocumentOperation::Archive, $body, $idempotencyKey, $documentId, $factuareaVersion, $xActiveProfile);
    }
    /** @return DocumentResponse<StreamInterface> */
    public function downloadDocument(string $documentId, ?int $documentVersion = null, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->transport->download('/crm/documents/'.strtolower(Wire::uuid($documentId)).'/download', $documentVersion, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** Same original operation/key/body; no separate public receipt endpoint exists.
     * @return DocumentResponse<Model\LibraryReceipt> */
    public function recover(DocumentWriteIntent $intent): DocumentResponse
    {
        if (!$intent->operation->library()) {
            throw new InvalidArgumentException('This intent belongs to the Opportunity document owner.');
        }

        return $this->receipt($this->transport->recover($intent));
    }
    /** @return DocumentResponse<Model\LibraryReceipt> */
    private function write(DocumentOperation $operation, Request\UploadDocumentRequest|Request\ReplaceDocumentVersionRequest|Request\ShareDocumentRequest|Request\RevokeDocumentShareRequest|Request\CasRequest $body, string $key, ?string $id, ?LocalDate $version, ?string $profile): DocumentResponse
    {
        return $this->receipt($this->transport->write(DocumentWriteIntent::create($operation, $body, $key, $this->transport->contextFingerprint(), $id, version: $version === null ? null : (string) $version, profile: $profile)));
    }
    /**
     * @param  DocumentResponse<Model\LibraryReceipt|Model\OpportunityDocumentLink|Model\UnlinkedDocument>  $response
     * @return DocumentResponse<Model\LibraryReceipt>
     */
    private function receipt(DocumentResponse $response): DocumentResponse
    {
        if (!$response->data instanceof Model\LibraryReceipt) {
            throw new LogicException('The native response is not a library receipt.');
        }

        return new DocumentResponse($response->data, $response->rawResponse);
    }
}
