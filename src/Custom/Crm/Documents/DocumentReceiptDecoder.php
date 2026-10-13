<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;
use Factuarea\Sdk\Custom\Crm\Documents\Model;
use InvalidArgumentException;

/** Original receipts are checked against the original intent, never a reloaded head or a guessed result. */
final class DocumentReceiptDecoder
{
    public static function decode(DocumentWriteIntent $intent, mixed $wire): Model\LibraryReceipt|Model\OpportunityDocumentLink|Model\UnlinkedDocument
    {
        if ($intent->operation->library()) {
            $receipt = Model\LibraryReceipt::fromWire($wire);
            if (($intent->targetId !== null && strcasecmp($receipt->id, $intent->targetId) !== 0)
                || ($intent->expectedVersion !== null && $receipt->version !== $intent->expectedVersion + 1)
                || ($intent->operation === DocumentOperation::Upload && $receipt->version !== 1)) {
                throw new InvalidArgumentException('The library receipt does not match its original identity/CAS.');
            }

            return $receipt;
        }
        if ($intent->operation === DocumentOperation::Unlink) {
            $receipt = Model\UnlinkedDocument::fromWire($wire);
            if (strcasecmp($receipt->id, $intent->targetId ?? '') !== 0 || strcasecmp($receipt->opportunityId, $intent->opportunityId ?? '') !== 0 || $receipt->version !== ($intent->expectedVersion ?? 0) + 1) {
                throw new InvalidArgumentException('The unlink receipt does not match its original association/CAS.');
            }

            return $receipt;
        }
        $receipt = Model\OpportunityDocumentLink::fromWire($wire);
        if ($receipt->status !== 'linked' || $receipt->document === null || strcasecmp($receipt->opportunityId, $intent->opportunityId ?? '') !== 0 || $receipt->documentType !== $intent->documentType
            || ($intent->documentId !== null && strcasecmp($receipt->documentId, $intent->documentId) !== 0) || ($intent->isPrimary === true && !$receipt->isPrimary)) {
            throw new InvalidArgumentException('The linked document receipt does not match its original owner intent.');
        }

        return $receipt;
    }
}
