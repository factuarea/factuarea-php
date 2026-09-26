# ResolvePurchaseScanDuplicateV1Request


## Fields

| Field                                                          | Type                                                           | Required                                                       | Description                                                    |
| -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- |
| `expectedVersion`                                              | *int*                                                          | :heavy_check_mark:                                             | N/A                                                            |
| `resolution`                                                   | [Components\Resolution](../../Models/Components/Resolution.md) | :heavy_check_mark:                                             | N/A                                                            |
| `purchaseInvoiceId`                                            | *?string*                                                      | :heavy_minus_sign:                                             | Required when resolution is link_existing.                     |