# VerifyClientCensusRequest

Public REST API v1 — POST /v1/clients/census-verification.

Verifies a THIRD PARTY's name + tax_id pair (invoice recipient) against
the AEAT census (VNifV2). Max lengths mirror the Company BC value objects
consumed by the bridge (`TaxIdentifier` ≤ 20, `CompanyName` ≤ 100).


## Fields

| Field                                                                                    | Type                                                                                     | Required                                                                                 | Description                                                                              |
| ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| `taxId`                                                                                  | *string*                                                                                 | :heavy_check_mark:                                                                       | Spanish tax identifier (NIF/CIF/NIE) of the third party to check against the AEAT census |
| `name`                                                                                   | *string*                                                                                 | :heavy_check_mark:                                                                       | Name or business name of the third party (the name + tax ID pair is verified together)   |