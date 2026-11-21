# SeriesStats

Number of numbering series of the authenticated company, archived ones included, in total and per document type. Returned by `GET /v1/companies/{company}/series/stats`.


## Fields

| Field                                         | Type                                          | Required                                      | Description                                   |
| --------------------------------------------- | --------------------------------------------- | --------------------------------------------- | --------------------------------------------- |
| `total`                                       | *int*                                         | :heavy_check_mark:                            | All the series of the company, of every type. |
| `invoice`                                     | *int*                                         | :heavy_check_mark:                            | Invoice series.                               |
| `quote`                                       | *int*                                         | :heavy_check_mark:                            | Quote series.                                 |
| `deliveryNote`                                | *int*                                         | :heavy_check_mark:                            | Delivery note series.                         |
| `proforma`                                    | *int*                                         | :heavy_check_mark:                            | Proforma series.                              |
| `transportDocument`                           | *int*                                         | :heavy_check_mark:                            | Transport document series.                    |