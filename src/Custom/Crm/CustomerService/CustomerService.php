<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService;

use Brick\DateTime\LocalDate;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\SDKConfiguration;
use Generator;
use InvalidArgumentException;
use LogicException;

/** Handwritten current native v1 subset. Construct from the existing SDK without changing its generated API or pinned spec. */
final readonly class CustomerService
{
    private TicketTransport $transport;
    public function __construct(SDKConfiguration $configuration)
    {
        $this->transport = new TicketTransport($configuration);
    }
    public static function fromSdk(Factuarea $sdk): self
    {
        return new self($sdk->sdkConfiguration);
    }
    /** @return TicketResponse<Model\Ticket> */
    public function getServiceTicket(string $ticketId, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): TicketResponse
    {
        $id = strtolower(Wire::uuid($ticketId));

        return $this->transport->read('customer_service.get', '/crm/service-tickets/'.$id, [], static function (mixed $wire) use ($id): Model\Ticket {
            $ticket = Model\Ticket::fromWire($wire);
            if (strcasecmp($ticket->id, $id) !== 0) {
                throw new InvalidArgumentException('The ticket read belongs to another owner identity.');
            }

            return $ticket;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** @return TicketResponse<Model\TicketPage> */
    public function listServiceTickets(?Request\TicketFilters $filters = null, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): TicketResponse
    {
        $filters ??= new Request\TicketFilters;

        return $this->transport->read('customer_service.list', '/crm/service-tickets', $filters->toArray(), static function (mixed $wire) use ($filters): Model\TicketPage {
            $page = Model\TicketPage::fromWire($wire);
            if ($page->page !== $filters->page || $page->perPage !== $filters->perPage) {
                throw new InvalidArgumentException('The ticket page does not match its original query.');
            }

            return $page;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** Offset pagination is not a cross-page snapshot; ACL is rechecked by the native owner on each read.
     * @return Generator<int,Model\Ticket>
     */
    public function iterateServiceTickets(?Request\TicketFilters $filters = null, int $maxPages = 1000, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): Generator
    {
        Wire::integer($maxPages);
        $filters ??= new Request\TicketFilters;
        for ($offset = 0; $offset < $maxPages; $offset++) {
            $query = $filters->atPage($filters->page + $offset);
            $result = $this->listServiceTickets($query, $factuareaVersion, $xActiveProfile)->data;
            foreach ($result->items as $item) {
                yield $item;
            }
            if ($result->items === [] || $result->page * $result->perPage >= $result->total) {
                return;
            }
        }
        throw new LogicException('Ticket iteration reached its explicit maximum page count.');
    }
    /** @return TicketResponse<Model\Timeline> */
    public function getServiceTicketTimeline(string $ticketId, ?Request\PageRequest $pagination = null, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): TicketResponse
    {
        $id = strtolower(Wire::uuid($ticketId));
        $pagination ??= new Request\PageRequest;

        return $this->transport->read('customer_service.timeline', '/crm/service-tickets/'.$id.'/timeline', $pagination->toArray(), static function (mixed $wire) use ($id, $pagination): Model\Timeline {
            $timeline = Model\Timeline::fromWire($wire);
            if (strcasecmp($timeline->id, $id) !== 0 || $timeline->page !== $pagination->page || $timeline->perPage !== $pagination->perPage) {
                throw new InvalidArgumentException('The timeline does not match its original parent/query.');
            }

            return $timeline;
        }, $factuareaVersion === null ? null : (string) $factuareaVersion, $xActiveProfile);
    }
    /** Native CRM-168 stage/status mapping; no caller status, WIP or approval fields.
     * @return TicketResponse<Model\TicketReceipt>
     */
    public function moveServiceTicketToStage(string $ticketId, Request\MoveToStageRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): TicketResponse
    {
        return $this->write(TicketOperation::MoveToStage, $ticketId, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }
    /** @return TicketResponse<Model\TicketReceipt> */
    public function attachServiceTicketDocument(string $ticketId, Request\AttachDocumentRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): TicketResponse
    {
        return $this->write(TicketOperation::AttachDocument, $ticketId, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }
    /** Use the server-owned attachment id from an authorized ticket/timeline read, with the current ticket CAS.
     * @return TicketResponse<Model\TicketReceipt>
     */
    public function detachServiceTicketDocument(string $ticketId, Request\DetachDocumentRequest $body, string $idempotencyKey, ?LocalDate $factuareaVersion = null, ?string $xActiveProfile = null): TicketResponse
    {
        return $this->write(TicketOperation::DetachDocument, $ticketId, $body, $idempotencyKey, $factuareaVersion, $xActiveProfile);
    }
    /** No head reload changes the original body/CAS/key. Recovery invokes only the original operation.
     * @return TicketResponse<Model\TicketReceipt>
     */
    public function recover(TicketWriteIntent $intent): TicketResponse
    {
        return $this->transport->recover($intent);
    }
    /** @return TicketResponse<Model\TicketReceipt> */
    private function write(TicketOperation $operation, string $id, Request\MoveToStageRequest|Request\AttachDocumentRequest|Request\DetachDocumentRequest $body,
        string $key, ?LocalDate $version, ?string $profile): TicketResponse
    {
        return $this->transport->write(TicketWriteIntent::create($operation, $id, $body, $key, $this->transport->contextFingerprint(),
            $version === null ? null : (string) $version, $profile));
    }
}
