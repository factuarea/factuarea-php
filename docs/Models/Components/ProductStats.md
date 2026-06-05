# ProductStats

Aggregated summary of the product catalog of the authenticated company: total, active, out of stock and low stock. Returned by `GET /v1/products/stats`.


## Fields

| Field                                                   | Type                                                    | Required                                                | Description                                             | Example                                                 |
| ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- |
| `totalProducts`                                         | *int*                                                   | :heavy_check_mark:                                      | Total number of products registered in the company.     | 248                                                     |
| `activeProducts`                                        | *int*                                                   | :heavy_check_mark:                                      | Productos marcados como activos.                        | 231                                                     |
| `outOfStockCount`                                       | *int*                                                   | :heavy_check_mark:                                      | Productos sin stock disponible (stock = 0).             | 12                                                      |
| `lowStockCount`                                         | *int*                                                   | :heavy_check_mark:                                      | Products whose stock is below the configured threshold. | 18                                                      |