# BulkStatusSalesOrdersRequestNewStatus

Target status of the transition: `confirmed`, `cancelled` or `closed`. Those three are the only ones that can be requested — the four consumption states are derived from delivery notes and invoices, and `draft` is never a target.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Confirmed` | confirmed   |
| `Cancelled` | cancelled   |
| `Closed`    | closed      |