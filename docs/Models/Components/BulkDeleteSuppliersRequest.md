# BulkDeleteSuppliersRequest

Public REST API v1 — POST /v1/suppliers/bulk-delete (canónico) y
DELETE /v1/suppliers/bulk (deprecado). Mismo request para ambas rutas.

Body: `{ ids: string[] }`. Acepta entre 1 y 200 identificadores (valor
UUID v7). La validación de pertenencia al tenant la realiza el Handler
(filtrado por company_id); los identificadores ajenos se ignoran
silenciosamente y aparecerán en `failed`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |