# ReturnReason

HEADER reason of the return, out of a closed catalog, or `null`. It is the optional one: the reason of each LINE is mandatory, and when the header one is not sent it is derived from the lines.


## Values

| Name                  | Value                 |
| --------------------- | --------------------- |
| `MerchandiseReturned` | merchandise_returned  |
| `OrderCancelled`      | order_cancelled       |
| `CustomerRequest`     | customer_request      |
| `ProductDefective`    | product_defective     |