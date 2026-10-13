<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;
use Brick\DateTime\LocalDate;
use Factuarea\Sdk\SDKConfiguration;
use Generator;
use InvalidArgumentException;
use LogicException;

/** Native Quote/Invoice/Contract/Recurring references; this client creates no fiscal owner or authority. */
final readonly class OpportunityDocuments
{
    private DocumentTransport $transport;
    public function __construct(SDKConfiguration $configuration)
    {
        $this->transport = new DocumentTransport($configuration);
    }
    /** @return DocumentResponse<Model\OpportunityDocumentPage> */
    public function listDocuments(string $opportunityId, ?Request\OpportunityDocumentFilters $filters = null, ?string $cursor = null, int $limit = 25, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        $id = strtolower(Wire::uuid($opportunityId));
        $this->limit($limit);
        $query = ['filters' => ($filters ?? new Request\OpportunityDocumentFilters)->toJson(), 'limit' => $limit];
        if ($cursor !== null) {
            $query['cursor'] = Wire::text($cursor, 8192);
        }

        return $this->transport->read('crm_opportunitydocuments.list', '/crm/opportunitydocuments/'.$id.'/documents', $query, static function (mixed $wire) use ($id): Model\OpportunityDocumentPage {
            $result = Model\OpportunityDocumentPage::fromWire($wire);
            if (strcasecmp($result->opportunityId, $id) !== 0) {
                throw new InvalidArgumentException('The document page belongs to another Opportunity.');
            }

            return $result;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** @return Generator<int,Model\OpportunityDocumentLink> */
    public function iterateDocuments(string $opportunityId, ?Request\OpportunityDocumentFilters $filters = null, int $limit = 25, int $maxPages = 1000, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Generator
    {
        Wire::integer($maxPages);
        $cursor = null;
        $seen = [];
        for ($page = 0; $page < $maxPages; $page++) {
            $result = $this->listDocuments($opportunityId, $filters, $cursor, $limit, $factuareaVersion, $xActiveProfile)->data;
            foreach ($result->items as $item) {
                yield $item;
            }
            if ($result->nextCursor === null) {
                return;
            }
            if (in_array($result->nextCursor, $seen, true)) {
                throw new LogicException('The native cursor did not advance.');
            }
            $cursor = $result->nextCursor;
            $seen[] = $cursor;
        }
        throw new LogicException('Document iteration reached its explicit maximum page count.');
    }
    /** The candidate revision is read-only; the native link request has no invented owner-CAS field.
     * @return DocumentResponse<Model\CandidatePage> */
    public function listCandidates(string $opportunityId, string $documentType, string $search = '', ?string $cursor = null, int $limit = 25, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        $id = strtolower(Wire::uuid($opportunityId));
        Wire::documentType($documentType);
        $this->limit($limit);
        $query = ['document_type' => $documentType, 'search' => Wire::text($search, 100, true), 'limit' => $limit];
        if ($cursor !== null) {
            $query['cursor'] = Wire::text($cursor, 32768);
        }

        return $this->transport->read('crm_opportunitydocuments.candidates', '/crm/opportunities/'.$id.'/documents/candidates', $query, static function (mixed $wire) use ($id, $documentType): Model\CandidatePage {
            $result = Model\CandidatePage::fromWire($wire);
            if (strcasecmp($result->opportunityId, $id) !== 0 || $result->documentType !== $documentType) {
                throw new InvalidArgumentException('The candidate page belongs to another parent/owner.');
            }

            return $result;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** @return DocumentResponse<Model\FinancialSummary> */
    public function financialSummary(string $opportunityId, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        $id = strtolower(Wire::uuid($opportunityId));

        return $this->transport->read('crm_opportunitydocuments.financial_summary', '/crm/opportunitydocuments/'.$id.'/financial-summary', [], static function (mixed $wire) use ($id): Model\FinancialSummary {
            $result = Model\FinancialSummary::fromWire($wire);
            if (strcasecmp($result->opportunityId, $id) !== 0) {
                throw new InvalidArgumentException('The financial response belongs to another Opportunity.');
            }

            return $result;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** @return DocumentResponse<Model\OpportunityDocumentLink> */
    public function createQuote(string $opportunityId, Request\CreateOpportunityQuoteRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->linked($this->write(DocumentOperation::CreateQuote, $opportunityId, null, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile));
    }
    /** @return DocumentResponse<Model\OpportunityDocumentLink> */
    public function linkDocument(string $opportunityId, Request\LinkOpportunityDocumentRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        return $this->linked($this->write(DocumentOperation::Link, $opportunityId, null, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile));
    }
    /** @return DocumentResponse<Model\UnlinkedDocument> */
    public function unlinkDocument(string $opportunityId, string $linkId, Request\CasRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): DocumentResponse
    {
        $response = $this->write(DocumentOperation::Unlink, $opportunityId, $linkId, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
        if (!$response->data instanceof Model\UnlinkedDocument) {
            throw new LogicException('The native response is not an unlink receipt.');
        }

        return new DocumentResponse($response->data, $response->rawResponse);
    }
    /** Same original operation/key/body selects the private original receipt; no public effect id is requested.
     * @return DocumentResponse<Model\OpportunityDocumentLink|Model\UnlinkedDocument> */
    public function recover(DocumentWriteIntent $intent): DocumentResponse
    {
        if ($intent->operation->library()) {
            throw new InvalidArgumentException('This intent belongs to the document library.');
        }
        $response = $this->transport->recover($intent);
        if ($response->data instanceof Model\LibraryReceipt) {
            throw new LogicException('The native response is not an Opportunity receipt.');
        }

        return new DocumentResponse($response->data, $response->rawResponse);
    }
    /** @return DocumentResponse<Model\LibraryReceipt|Model\OpportunityDocumentLink|Model\UnlinkedDocument> */
    private function write(DocumentOperation $operation, string $parent, ?string $link, Request\CreateOpportunityQuoteRequest|Request\LinkOpportunityDocumentRequest|Request\CasRequest $body, string $key, ?LocalDate $version, ?string $profile): DocumentResponse
    {
        return $this->transport->write(DocumentWriteIntent::create($operation, $body, $key, $this->transport->contextFingerprint(), $link, $parent, $version === null ? null : (string) $version, $profile));
    }
    /**
     * @param  DocumentResponse<Model\LibraryReceipt|Model\OpportunityDocumentLink|Model\UnlinkedDocument>  $response
     * @return DocumentResponse<Model\OpportunityDocumentLink>
     */
    private function linked(DocumentResponse $response): DocumentResponse
    {
        if (!$response->data instanceof Model\OpportunityDocumentLink) {
            throw new LogicException('The native response is not a linked-document receipt.');
        }

        return new DocumentResponse($response->data, $response->rawResponse);
    }
    private function limit(int $limit): void
    {
        Wire::integer($limit);
        if ($limit > 100) {
            throw new InvalidArgumentException('Native document page size cannot exceed 100.');
        }
    }
}
