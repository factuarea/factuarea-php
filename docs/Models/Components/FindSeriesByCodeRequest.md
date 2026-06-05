# FindSeriesByCodeRequest

Public REST API v1 — POST /v1/series/find-by-code.

Looks up a series by its `code` (normalized to uppercase in the handler)
within the authenticated company. The `code` travels in the JSON body (not in
query params) because it is a private attribute that should not end up in
proxy logs. `document_type` is optional and disambiguates matches when the
same `code` is associated with several types.


## Fields

| Field                                                                                                             | Type                                                                                                              | Required                                                                                                          | Description                                                                                                       |
| ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- |
| `code`                                                                                                            | *string*                                                                                                          | :heavy_check_mark:                                                                                                | N/A                                                                                                               |
| `documentType`                                                                                                    | [?Components\FindSeriesByCodeRequestDocumentType](../../Models/Components/FindSeriesByCodeRequestDocumentType.md) | :heavy_minus_sign:                                                                                                | N/A                                                                                                               |