# PublicApiV1InvoicesPdfFormat

Paper of the PDF: `a4` (default, with the company template), `ticket_80` (80 mm thermal roll) or `ticket_58` (58 mm roll). Any other value returns 422 with a `parameter_invalid_enum` entry (and its `allowed_values`) in `error.errors[]`. The format does not change the company template; each format is rendered and cached separately and has its own `ETag`.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `A4`       | a4         |
| `Ticket80` | ticket_80  |
| `Ticket58` | ticket_58  |