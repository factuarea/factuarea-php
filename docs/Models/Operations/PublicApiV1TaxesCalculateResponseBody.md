# PublicApiV1TaxesCalculateResponseBody


## Fields

| Field                                                                                                  | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `data`                                                                                                 | [Components\TaxCalculation](../../Models/Components/TaxCalculation.md)                                 | :heavy_check_mark:                                                                                     | Result of applying a tax to a base amount. Returned by `POST /v1/companies/{company}/taxes/calculate`. |