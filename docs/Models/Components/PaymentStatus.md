# PaymentStatus

Derived payment status, NOT a persisted domain state (the model keeps 4 statuses). `overdue` derives from `pending` + `due_date < today` and prevails in presentation.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `Pending`       | pending         |
| `PartiallyPaid` | partially_paid  |
| `Paid`          | paid            |
| `Overdue`       | overdue         |