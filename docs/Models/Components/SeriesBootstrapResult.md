# SeriesBootstrapResult

Result of bootstrapping the numbering of a company: one entry per document type of the public surface, always in the same order (`invoice`, `quote`, `delivery_note`, `proforma`).


## Fields

| Field                                                                                     | Type                                                                                      | Required                                                                                  | Description                                                                               |
| ----------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------- |
| `results`                                                                                 | array<[Components\SeriesBootstrapEntry](../../Models/Components/SeriesBootstrapEntry.md)> | :heavy_check_mark:                                                                        | One entry per document type evaluated.                                                    |