<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Examples;

use Factuarea\Sdk\Custom\Crm\CustomerService\CustomerService;
use Factuarea\Sdk\Custom\Crm\CustomerService\Model\TicketReceipt;
use Factuarea\Sdk\Custom\Crm\CustomerService\Request\AttachDocumentRequest;
use Factuarea\Sdk\Custom\Crm\CustomerService\Request\DetachDocumentRequest;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWriteUnconfirmed;
use LogicException;

/** Called by the integrator's authorized server job, after reading the current ticket and authorized document version.
 * The caller retains unique operation keys durably; inclusion alone performs no network request.
 */
function attachTicketDocument(CustomerService $tickets, string $ticketId, int $currentTicketVersion, string $documentId, int $documentVersion, string $originalKey): TicketReceipt
{
    try {
        return $tickets->attachServiceTicketDocument($ticketId, new AttachDocumentRequest($currentTicketVersion, $documentId, $documentVersion), $originalKey)->data;
    } catch (TicketWriteUnconfirmed $uncertain) {
        // Persist $uncertain->intent in this job; later recover only that same POST/key/body.
        return $tickets->recover($uncertain->intent)->data;
    }
}

/** Detach consumes the server attachment UUID and current ticket CAS from an authorized read.
 * It never chooses a guessed UUID or changes the key of an ambiguous previous write.
 */
function detachVisibleTicketDocument(CustomerService $tickets, string $ticketId, string $attachmentId, string $originalKey): TicketReceipt
{
    $ticket = $tickets->getServiceTicket($ticketId)->data;
    $attachments = $ticket->attachments() ?? throw new LogicException('The ticket attachments are omitted by the current read contract.');
    $visible = false;
    foreach ($attachments as $attachment) {
        if (strcasecmp($attachment->id, $attachmentId) === 0) {
            $visible = true;
            break;
        }
    }
    if (!$visible) {
        throw new LogicException('The requested attachment is absent from the authorized ticket projection.');
    }
    try {
        return $tickets->detachServiceTicketDocument($ticket->id, new DetachDocumentRequest($ticket->version, $attachmentId), $originalKey)->data;
    } catch (TicketWriteUnconfirmed $uncertain) {
        return $tickets->recover($uncertain->intent)->data;
    }
}
