# FindProductByExternalIdRequest

Look up a product by its `external_id` (the integration key that maps it to a record in a third-party ERP/CRM/e-commerce system) within your company. The value travels in the body (not the URL) because an external key may contain characters that would break a path.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `externalId`       | *string*           | :heavy_check_mark: | N/A                |