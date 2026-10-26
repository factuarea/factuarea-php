# SignDeliveryNoteRequest

Sign a delivery note on receipt. Required: `signed_by` (name of the person who receives it), `recipient_dni` (Spanish DNI/NIE of the signer) and `signature_image_base64` (the signature as a raw base64 PNG, without the `data:` prefix). Optional: `signed_at` (ISO 8601, defaults to now). A signature image that cannot be decoded or is too large returns 422 `invalid_param_format` or `payload_too_large`.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `signedBy`                                                    | *string*                                                      | :heavy_check_mark:                                            | N/A                                                           |
| `recipientDni`                                                | *string*                                                      | :heavy_check_mark:                                            | N/A                                                           |
| `signatureImageBase64`                                        | *string*                                                      | :heavy_check_mark:                                            | N/A                                                           |
| `signedAt`                                                    | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | N/A                                                           |