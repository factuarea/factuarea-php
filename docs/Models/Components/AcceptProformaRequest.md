# AcceptProformaRequest

Public REST API v1 — POST /v1/proformas/{uuid}/accept.

Body opcional: `reason` (string ≤500), `metadata` (objeto ≤50 keys,
≤500 chars/valor — validado con el VO `Metadata`).


## Fields

| Field                                                                                                         | Type                                                                                                          | Required                                                                                                      | Description                                                                                                   | Example                                                                                                       |
| ------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- |
| `reason`                                                                                                      | *?string*                                                                                                     | :heavy_minus_sign:                                                                                            | N/A                                                                                                           |                                                                                                               |
| `metadata`                                                                                                    | array<string, *string*>                                                                                       | :heavy_minus_sign:                                                                                            | Up to 50 key-value pairs for storing additional structured data. Values must be strings up to 500 characters. | {<br/>"erp_code": "IVA-GEN",<br/>"ledger_account": "477000"<br/>}                                             |