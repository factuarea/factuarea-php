# PriceListItemCollection

Cursor-paginated price entries. `has_more` and `next_cursor` are computed over the FILTERED set when `?search=` is supplied, not over the whole price list.


## Fields

| Field                                                                       | Type                                                                        | Required                                                                    | Description                                                                 |
| --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- |
| `data`                                                                      | array<[Components\PriceListItem](../../Models/Components/PriceListItem.md)> | :heavy_check_mark:                                                          | N/A                                                                         |
| `hasMore`                                                                   | *bool*                                                                      | :heavy_check_mark:                                                          | N/A                                                                         |
| `nextCursor`                                                                | *string*                                                                    | :heavy_check_mark:                                                          | N/A                                                                         |