# FindRecordByInvoiceNumberV1Request

Public REST API v1 — POST /v1/verifactu/records/find-by-invoice-number.

Body `{"series": "F", "number": "2026-019"}`. Canonical V1 validation
(NEVER a generic `validation_error`/422):
 - `series` or `number` missing  → 400 `parameter_missing`.
 - mutually exclusive fields     → 400 `parameter_unknown` (subcode
   `mutually_exclusive_query_params`) if `huella` or `aeat_csv` are present.

The 404 is emitted by the handler via `RecordNotFoundException::withInvoiceNumber()`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `series`           | *?string*          | :heavy_minus_sign: | N/A                |
| `number`           | *?string*          | :heavy_minus_sign: | N/A                |