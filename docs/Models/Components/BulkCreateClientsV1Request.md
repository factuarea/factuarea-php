# BulkCreateClientsV1Request

Create clients in bulk. `clients[]` holds up to 500 client payloads and `dry_run` (default `false`) validates each row without persisting. Per-row rules (format, duplicate `external_id`/`tax_id`, AEAT census) are reported per row instead of failing the whole batch.


## Fields

| Field                  | Type                   | Required               | Description            |
| ---------------------- | ---------------------- | ---------------------- | ---------------------- |
| `clients`              | array<array<*string*>> | :heavy_check_mark:     | N/A                    |
| `dryRun`               | *?bool*                | :heavy_minus_sign:     | N/A                    |