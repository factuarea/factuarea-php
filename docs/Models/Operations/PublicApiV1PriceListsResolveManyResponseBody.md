# PublicApiV1PriceListsResolveManyResponseBody


## Fields

| Field                                                                                                     | Type                                                                                                      | Required                                                                                                  | Description                                                                                               |
| --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| `data`                                                                                                    | array<[Components\ResolvedCatalogPricePreview](../../Models/Components/ResolvedCatalogPricePreview.md)>   | :heavy_check_mark:                                                                                        | Resolved prices of the batch, one per selection sent. The order is not guaranteed: match them by `index`. |