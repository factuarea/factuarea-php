# DeliveryNotes.PickingQueue

## Overview

### Available Operations

* [publicApiV1DeliveryNotesPickingQueueList](#publicapiv1deliverynotespickingqueuelist) - List the delivery note picking queue

## publicApiV1DeliveryNotesPickingQueueList

List the work queue of the warehouse — the delivery notes that still have work pending on their physical axis — with cursor-based pagination. Filter by fulfilment status, by carrier, by origin warehouse and by the sales order the note came from; the last three take the public id of the resource and are resolved inside your company, so an id that belongs to another company returns an empty page and never a row. Sort by the date of the note or by its expected delivery date. Each item carries only what a work queue needs: the delivery note by its public id and its number, its fulfilment status with its label, and the origin warehouse and the source sales order as OPAQUE ids when it has them. It deliberately carries neither the carrier, nor the two dates you can sort by, nor the picking lines: resolving the name of a warehouse or the number of an order for every row would turn one listing into an extra read per row, and the lines belong to the picking list of each note, which has its own operation.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.picking_queue.list" method="get" path="/companies/{company}/delivery-notes/picking-queue" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1DeliveryNotesPickingQueueListRequest(
    company: 'Kulas, Schoen and DuBuque',
    sort: Operations\PublicApiV1DeliveryNotesPickingQueueListSort::MinusDeliveryDate,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->deliveryNotes->pickingQueue->publicApiV1DeliveryNotesPickingQueueList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1DeliveryNotesPickingQueueListRequest](../../Models/Operations/PublicApiV1DeliveryNotesPickingQueueListRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1DeliveryNotesPickingQueueListResponse](../../Models/Operations/PublicApiV1DeliveryNotesPickingQueueListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |