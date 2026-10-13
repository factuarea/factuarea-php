<?php

/** Composable server-side example. The integrator owns private credentials and durable original keys. */
declare(strict_types=1);

use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Crm\Documents\DocumentResponse;
use Factuarea\Sdk\Custom\Crm\Documents\DocumentWriteIntent;
use Factuarea\Sdk\Custom\Crm\Documents\Model\OpportunityDocumentLink;
use Factuarea\Sdk\Custom\Crm\Documents\Model\UnlinkedDocument;
use Factuarea\Sdk\Custom\Crm\Documents\Request\LinkOpportunityDocumentRequest;
use Factuarea\Sdk\Factuarea;

/** @return DocumentResponse<OpportunityDocumentLink> */
function attachCanonicalQuote(Factuarea $sdk, string $opportunityId, string $nativeQuoteId, string $persistedOriginalKey): DocumentResponse
{
    return FactuareaClient::opportunityDocuments($sdk)->linkDocument($opportunityId, new LinkOpportunityDocumentRequest('quote', $nativeQuoteId, false), $persistedOriginalKey);
}

/** Invoke explicitly after retaining a DocumentWriteUnconfirmed intent; do not generate a fresh key.
 * @return DocumentResponse<OpportunityDocumentLink|UnlinkedDocument> */
function recoverCanonicalOpportunityDocument(Factuarea $sdk, DocumentWriteIntent $originalIntent): DocumentResponse
{
    return FactuareaClient::opportunityDocuments($sdk)->recover($originalIntent);
}
