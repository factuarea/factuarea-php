# EntityLink

Links the task to a Factuarea document or contact in the same transaction. If the entity does not exist, belongs to another company or its module is not accessible, the call returns 404 `linked_entity_not_found` and the task is not created.


## Fields

| Field                                                                                    | Type                                                                                     | Required                                                                                 | Description                                                                              |
| ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| `type`                                                                                   | [Components\CreateTaskV1RequestType](../../Models/Components/CreateTaskV1RequestType.md) | :heavy_check_mark:                                                                       | Type of the entity.                                                                      |
| `id`                                                                                     | *string*                                                                                 | :heavy_check_mark:                                                                       | UUID of the entity.                                                                      |