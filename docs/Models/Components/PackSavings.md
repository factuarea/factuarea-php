# PackSavings

How much cheaper the pack is than its components bought separately. It is `null` in the parent when the pack saves nothing.


## Fields

| Field                                                                                         | Type                                                                                          | Required                                                                                      | Description                                                                                   |
| --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- |
| `gross`                                                                                       | *string*                                                                                      | :heavy_check_mark:                                                                            | Saving with VAT.                                                                              |
| `percent`                                                                                     | *float*                                                                                       | :heavy_check_mark:                                                                            | Saving in percent over the price of the components bought separately, rounded to one decimal. |