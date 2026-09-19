# TargetStatus

DESTINATION state of the physical axis: `pending`, `picking`, `prepared`, `handed_over`, `in_transit`, `delivered` or `failed`. Belonging to that catalog is NOT the same as being reachable: which destinations are admitted depends on where the delivery note is right now, and one the state machine does not admit is rejected as a business-rule violation naming the current state and the admitted ones — never as a permission problem, because the caller does have permission and it is the document that does not admit the step. Delivery to the recipient is terminal; a failed delivery admits a second attempt back into transit; and a delivery note cancelled on its commercial side does not advance at all. This never changes the commercial status of the document.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Pending`    | pending      |
| `Picking`    | picking      |
| `Prepared`   | prepared     |
| `HandedOver` | handed_over  |
| `InTransit`  | in_transit   |
| `Delivered`  | delivered    |
| `Failed`     | failed       |