# UpdateReturnRequestReason

Header reason of the return, out of the closed set of four. Omitting it keeps the current one and `null` WITHDRAWS it, after which it is derived again from the lines: a return always ends up with a reason.


## Values

| Name                  | Value                 |
| --------------------- | --------------------- |
| `MerchandiseReturned` | merchandise_returned  |
| `OrderCancelled`      | order_cancelled       |
| `CustomerRequest`     | customer_request      |
| `ProductDefective`    | product_defective     |