# BulkDeleteRecurringInvoicesRequest

Public REST API v1 — POST /v1/recurring_invoices/bulk-delete.

Body requerido: `ids` (array de UUIDs entre 1 y 100). La pertenencia al
tenant se resuelve en el Handler — UUIDs desconocidos o de otra company se
reportan en `failed[]` (defense-in-depth contra enumeration cross-tenant).

La key del payload es `ids` (alineado con PurchaseInvoice/Quote/Supplier y
el spec `public-api-recurring-invoice-bulk-delete-cqrs`).


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |