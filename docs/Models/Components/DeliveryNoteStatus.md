# DeliveryNoteStatus

Delivery note lifecycle status exposed by the public API: `draft` (created, editable), `sent` (goods marked as delivered, no signature recorded yet), `signed` (delivered AND a recipient signature has been recorded), `invoiced` (converted into an invoice, terminal), `cancelled` (terminal). Note: the internal `delivered` state is surfaced as `sent` (without signature) or `signed` (with signature) — there is no `delivered` value in the public API. Recording a signature on a `sent` note moves it to `signed` (it does not introduce a brand-new lifecycle stage; the signature presence is the only difference).


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Draft`     | draft       |
| `Sent`      | sent        |
| `Signed`    | signed      |
| `Invoiced`  | invoiced    |
| `Cancelled` | cancelled   |