# SubstituteSimplifiedV1Request

Public REST API v1 — POST /v1/invoices/substitute-simplified.

Body: `client_id` (cliente destinatario), `simplified_invoice_ids`
(array de ids de facturas F2 a sustituir), `notes` opcional.


## Fields

| Field                  | Type                   | Required               | Description            |
| ---------------------- | ---------------------- | ---------------------- | ---------------------- |
| `clientId`             | *string*               | :heavy_check_mark:     | N/A                    |
| `notes`                | *?string*              | :heavy_minus_sign:     | N/A                    |
| `simplifiedInvoiceIds` | array<*string*>        | :heavy_check_mark:     | N/A                    |