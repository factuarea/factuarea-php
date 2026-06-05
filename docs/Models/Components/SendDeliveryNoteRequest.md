# SendDeliveryNoteRequest

Public REST API v1 — POST /v1/delivery_notes/{uuid}/send.

Required body: `email`. Optional: `subject`, `message` (max 2000 chars),
`template_id` (catalog id of the email template).

`template_id` is the integer identifier of the global system table
`templates` (shared catalog, without `company_id` or `uuid` column). It is
validated against the PK `id`, like the internal SPA endpoint. That is why
it does NOT follow the public UUID convention of the other FKs.


## Fields

| Field                                                                                                                                     | Type                                                                                                                                      | Required                                                                                                                                  | Description                                                                                                                               |
| ----------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| `email`                                                                                                                                   | *string*                                                                                                                                  | :heavy_check_mark:                                                                                                                        | N/A                                                                                                                                       |
| `subject`                                                                                                                                 | *?string*                                                                                                                                 | :heavy_minus_sign:                                                                                                                        | N/A                                                                                                                                       |
| `message`                                                                                                                                 | *?string*                                                                                                                                 | :heavy_minus_sign:                                                                                                                        | N/A                                                                                                                                       |
| `templateId`                                                                                                                              | *?int*                                                                                                                                    | :heavy_minus_sign:                                                                                                                        | uuid-audit-allow: global system catalog (`templates` table without<br/>company_id or uuid column; integer catalog PK, like the internal SPA). |