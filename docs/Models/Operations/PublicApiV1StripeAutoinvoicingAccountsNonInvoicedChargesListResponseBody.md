# PublicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesListResponseBody

Charges left out of invoicing by a rule, most recent first, with cursor pagination.


## Fields

| Field                                                                                | Type                                                                                 | Required                                                                             | Description                                                                          |
| ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| `data`                                                                               | array<[Components\NonInvoicedCharge](../../Models/Components/NonInvoicedCharge.md)>  | :heavy_check_mark:                                                                   | N/A                                                                                  |
| `hasMore`                                                                            | *bool*                                                                               | :heavy_check_mark:                                                                   | Whether there are more results after this page.                                      |
| `nextCursor`                                                                         | *string*                                                                             | :heavy_check_mark:                                                                   | Cursor to pass as `starting_after` to get the next page, or `null` on the last page. |