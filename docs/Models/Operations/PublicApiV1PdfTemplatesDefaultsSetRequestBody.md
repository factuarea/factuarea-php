# PublicApiV1PdfTemplatesDefaultsSetRequestBody

Internal reversible write: idempotency follows the current policy; a supplied key is replayed before CAS. Unknown fields are rejected. Until the company is prepared for revisioned templates (tenant initialization pending or revisioned rendering off) every library write returns 422 `pdf_presentation_not_ready` before any precondition or effect; reads stay available and no `Retry-After` applies.


## Fields

| Field                                                                     | Type                                                                      | Required                                                                  | Description                                                               |
| ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- |
| `expectedVersion`                                                         | *?int*                                                                    | :heavy_minus_sign:                                                        | N/A                                                                       |
| `companyTemplateId`                                                       | *?string*                                                                 | :heavy_minus_sign:                                                        | N/A                                                                       |
| `companyCommercialIdentityId`                                             | *?string*                                                                 | :heavy_minus_sign:                                                        | N/A                                                                       |
| `typeDefaults`                                                            | array<[Operations\TypeDefaults](../../Models/Operations/TypeDefaults.md)> | :heavy_minus_sign:                                                        | N/A                                                                       |