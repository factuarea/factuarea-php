# FindSeriesByCodeRequestDocumentType

Los tipos admitidos se DERIVAN de
`SeriesType::PUBLIC_API_V1_CREATABLE_TYPES` y no se escriben a mano
(edición autorizada del change `erp-v1-surface-sales-orders`, que
publica el tipo `sales_order`). Escrita a mano, la regla declaraba
los CUATRO tipos del arranque, así que una serie de pedido de venta
creada por la propia API pública no se podía desambiguar por su
tipo en esta búsqueda: la superficie dejaba crear algo que después
no sabía filtrar.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `Invoice`       | invoice         |
| `Quote`         | quote           |
| `DeliveryNote`  | delivery_note   |
| `Proforma`      | proforma        |
| `SalesOrder`    | sales_order     |
| `PurchaseOrder` | purchase_order  |