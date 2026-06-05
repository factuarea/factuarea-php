# FindRecordByHuellaV1Request

Public REST API v1 — POST /v1/verifactu/records/find-by-huella.

Body `{"huella": "<64-char-hex>"}`. Canonical V1 validation (NEVER a generic
`validation_error`/422):
 - `huella` missing              → 400 `parameter_missing` (param `huella`).
 - mutually exclusive fields     → 400 `parameter_unknown` (subcode
   `mutually_exclusive_query_params`) if `aeat_csv`, `series` or `number`
   appear together with `huella`.
 - invalid huella format         → 422 `business_rule_violation`
   (`InvalidHuellaException`, VO invariant; param `huella`).

The 404 (huella not found, multi-tenant) is emitted by the handler via
`RecordNotFoundException::withHuella()` (param `huella`).


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `huella`           | *?string*          | :heavy_minus_sign: | N/A                |