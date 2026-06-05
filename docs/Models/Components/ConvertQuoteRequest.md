# ConvertQuoteRequest

Public REST API v1 — POST /v1/quotes/{uuid}/convert.

Body requerido: `target` ∈ {invoice, proforma, delivery_note}.
Body opcional: `issued_on` (date), `due_on` (date).


## Fields

| Field                                                                                        | Type                                                                                         | Required                                                                                     | Description                                                                                  |
| -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- |
| `target`                                                                                     | [Components\ConvertQuoteRequestTarget](../../Models/Components/ConvertQuoteRequestTarget.md) | :heavy_check_mark:                                                                           | N/A                                                                                          |
| `issuedOn`                                                                                   | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                | :heavy_minus_sign:                                                                           | N/A                                                                                          |
| `dueOn`                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                | :heavy_minus_sign:                                                                           | N/A                                                                                          |