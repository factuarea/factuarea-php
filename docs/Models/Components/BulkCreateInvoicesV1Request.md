# BulkCreateInvoicesV1Request

Create invoices in bulk. `invoices[]` holds up to 100 invoice payloads and `dry_run` (default `false`) validates each row without persisting. Per-row rules (format, duplicate `external_id`, recipient AEAT census) are reported per row instead of failing the whole batch.


## Fields

| Field                  | Type                   | Required               | Description            |
| ---------------------- | ---------------------- | ---------------------- | ---------------------- |
| `invoices`             | array<array<*string*>> | :heavy_check_mark:     | N/A                    |
| `dryRun`               | *?bool*                | :heavy_minus_sign:     | N/A                    |