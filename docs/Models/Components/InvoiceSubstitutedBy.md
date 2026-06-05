# InvoiceSubstitutedBy

Reference to the full invoice (F3) that replaced this simplified invoice (F2). `null` when not applicable.


## Fields

| Field                                                  | Type                                                   | Required                                               | Description                                            | Example                                                |
| ------------------------------------------------------ | ------------------------------------------------------ | ------------------------------------------------------ | ------------------------------------------------------ | ------------------------------------------------------ |
| `id`                                                   | *string*                                               | :heavy_check_mark:                                     | UUID (v7) de la factura completa sustituta.            | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a50                   |
| `number`                                               | *string*                                               | :heavy_check_mark:                                     | Human-readable number of the replacement full invoice. | FAC-2026-00050                                         |