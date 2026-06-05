# TaxDefaultsForDocuments

Defaults grouped by tax category for a given document type. Each key contains the (possibly empty) list of taxes marked as default for that `docType` within its `type`.


## Fields

| Field                                                            | Type                                                             | Required                                                         | Description                                                      | Example                                                          |
| ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- |
| `vat`                                                            | array<[Components\Tax](../../Models/Components/Tax.md)>          | :heavy_check_mark:                                               | Taxes con `type=vat` que son default para el docType consultado. | []                                                               |
| `retention`                                                      | array<[Components\Tax](../../Models/Components/Tax.md)>          | :heavy_check_mark:                                               | N/A                                                              | []                                                               |
| `surcharge`                                                      | array<[Components\Tax](../../Models/Components/Tax.md)>          | :heavy_check_mark:                                               | N/A                                                              | []                                                               |
| `other`                                                          | array<[Components\Tax](../../Models/Components/Tax.md)>          | :heavy_check_mark:                                               | N/A                                                              | []                                                               |