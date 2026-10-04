# PublicApiV1InvoicesPdfLinkFormat

Paper of the PDF the signed link serves: `a4` (default, with the company template), `ticket_80` (80 mm thermal roll) or `ticket_58` (58 mm roll). Any other value returns 422 with a `parameter_invalid_enum` entry (and its `allowed_values`) in `error.errors[]`. Each format is rendered and cached separately.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `A4`       | a4         |
| `Ticket80` | ticket_80  |
| `Ticket58` | ticket_58  |