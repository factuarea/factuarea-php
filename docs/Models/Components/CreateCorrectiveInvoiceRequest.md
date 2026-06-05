# CreateCorrectiveInvoiceRequest

Public REST API v1 — POST /v1/invoices/{uuid}/corrective.

Body:
 - `correction_reason` (canonical slug BR-INV-018) — required field. Maps to
   a fine-grained VeriFactu code R1..R4 (error_fundado→R1, concurso→R2,
   incobrable→R3, rest→R4). Replaces the former free-text `reason` that the
   controller hardcoded to `'otras'` (fiscal bug).
 - `correction_type` (`full` or `partial`) — canonical field.
 - `corrective_type` — deprecated alias kept for backward compatibility.
   If sent and `correction_type` is absent, it is promoted automatically.
 - `notes` (optional string) — free-text traceability.
 - `lines` (array, required if `correction_type=partial`).


## Fields

| Field                                                                                                                 | Type                                                                                                                  | Required                                                                                                              | Description                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------- |
| `correctionReason`                                                                                                    | [Components\CorrectionReason](../../Models/Components/CorrectionReason.md)                                            | :heavy_check_mark:                                                                                                    | N/A                                                                                                                   |
| `correctionType`                                                                                                      | [Components\CorrectionType](../../Models/Components/CorrectionType.md)                                                | :heavy_check_mark:                                                                                                    | N/A                                                                                                                   |
| `notes`                                                                                                               | *?string*                                                                                                             | :heavy_minus_sign:                                                                                                    | N/A                                                                                                                   |
| `lines`                                                                                                               | array<[Components\CreateCorrectiveInvoiceRequestLine](../../Models/Components/CreateCorrectiveInvoiceRequestLine.md)> | :heavy_minus_sign:                                                                                                    | N/A                                                                                                                   |