# DeliveryNotes.SignatureAudits

## Overview

### Available Operations

* [publicApiV1DeliveryNotesSignatureAuditsForget](#publicapiv1deliverynotessignatureauditsforget) - Forget delivery note signature PII

## publicApiV1DeliveryNotesSignatureAuditsForget

GDPR Art. 17 (right to erasure) — remove the personal data (recipient name/DNI) from a signature audit log entry while preserving the non-PII audit trail required for LSSI-CE compliance. The `{auditId}` is the numeric primary key of the signature audit record. Requires the `delivery_notes:gdpr_forget` scope.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.signature_audits.forget" method="post" path="/delivery_notes/signature-audits/{auditId}/forget" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->signatureAudits->publicApiV1DeliveryNotesSignatureAuditsForget(
    auditId: '<id>'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `auditId`          | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1DeliveryNotesSignatureAuditsForgetResponse](../../Models/Operations/PublicApiV1DeliveryNotesSignatureAuditsForgetResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 409, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |