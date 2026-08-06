# EmailDeliveryStatus

Status of the send. It describes the hand-off to the OUTGOING SMTP SERVER, NOT real delivery to the recipient's mailbox: `queued` (accepted, waiting for the worker), `sending` (being handed off), `sent` (the outgoing mail server accepted the message) and `failed` (every attempt failed). A `sent` message can still bounce or land in spam afterwards without the platform finding out. There are deliberately no `delivered`, `bounced` or `opened` values: observing them would require a mail provider with delivery webhooks, which is out of scope.


## Values

| Name      | Value     |
| --------- | --------- |
| `Queued`  | queued    |
| `Sending` | sending   |
| `Sent`    | sent      |
| `Failed`  | failed    |