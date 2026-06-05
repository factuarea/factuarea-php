# FindRecordByAeatCsvV1Request

Public REST API v1 — POST /v1/verifactu/records/find-by-csv.

Body `{"aeat_csv": "X-Y-Z"}`. Canonical V1 validation (NEVER a generic
`validation_error`/422):
 - `aeat_csv` missing            → 400 `parameter_missing` (param `aeat_csv`).
 - mutually exclusive fields     → 400 `parameter_unknown` (subcode
   `mutually_exclusive_query_params`).

The handler normalizes (`str_replace('-', '', strtoupper($csv))`) before the
query (BR-VFC-016). The 404 is emitted via `RecordNotFoundException::withAeatCsv()`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `aeatCsv`          | *?string*          | :heavy_minus_sign: | N/A                |