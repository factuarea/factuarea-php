# SignDeliveryNoteRequest

Public REST API v1 — POST /v1/delivery_notes/{uuid}/sign.

Optional body: `signed_by` (alias of `recipient_name`, BC invariant),
`recipient_dni` (BC invariant — Spanish DNI/NIE, required by
`SignatureData`), `signature_image_base64` (raw base64 PNG; decode +
size + magic bytes are validated by the controller to respond 422
`payload_too_large`/`invalid_param_format`), `signed_at` (ISO 8601
optional, default now).

The controller converts `signed_by` → `recipient_name` and prefixes the
base64 with `data:image/png;base64,` before dispatching to the BC.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `signedBy`                                                    | *string*                                                      | :heavy_check_mark:                                            | N/A                                                           |
| `recipientDni`                                                | *string*                                                      | :heavy_check_mark:                                            | N/A                                                           |
| `signatureImageBase64`                                        | *string*                                                      | :heavy_check_mark:                                            | N/A                                                           |
| `signedAt`                                                    | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | N/A                                                           |