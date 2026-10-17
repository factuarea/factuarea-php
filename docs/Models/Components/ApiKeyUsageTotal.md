# ApiKeyUsageTotal

Sum of the counters of the period.


## Fields

| Field                                               | Type                                                | Required                                            | Description                                         | Example                                             |
| --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- |
| `requestsCount`                                     | *int*                                               | :heavy_check_mark:                                  | Requests made with the key.                         | 1280                                                |
| `successfulCount`                                   | *int*                                               | :heavy_check_mark:                                  | Requests answered with a 2xx or 3xx status.         | 1204                                                |
| `clientErrorCount`                                  | *int*                                               | :heavy_check_mark:                                  | Requests answered with a 4xx status other than 429. | 61                                                  |
| `rateLimitedCount`                                  | *int*                                               | :heavy_check_mark:                                  | Requests answered with 429.                         | 7                                                   |
| `errorCount`                                        | *int*                                               | :heavy_check_mark:                                  | Requests answered with a 5xx status.                | 8                                                   |