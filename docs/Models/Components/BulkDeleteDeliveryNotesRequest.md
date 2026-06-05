# BulkDeleteDeliveryNotesRequest

Public REST API v1 — POST /v1/delivery_notes/bulk-delete.

Body requerido: `ids` (array de UUIDs entre 1 y 100). Los UUIDs deben
ser strings con formato UUID; la pertenencia al tenant se verifica en el
controller (los UUIDs desconocidos o cross-tenant se reportan como
fallidos en la respuesta, no como 404 global).


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |