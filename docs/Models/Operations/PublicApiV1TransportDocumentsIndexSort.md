# PublicApiV1TransportDocumentsIndexSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `transport_date`, `number`, `created`, `updated`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, documents are ordered by transport date, newest first.


## Values

| Name                 | Value                |
| -------------------- | -------------------- |
| `TransportDate`      | transport_date       |
| `MinusTransportDate` | -transport_date      |
| `Number`             | number               |
| `MinusNumber`        | -number              |
| `Created`            | created              |
| `MinusCreated`       | -created             |
| `Updated`            | updated              |
| `MinusUpdated`       | -updated             |