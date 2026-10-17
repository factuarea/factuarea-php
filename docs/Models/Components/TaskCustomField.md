# TaskCustomField


## Fields

| Field                                                                     | Type                                                                      | Required                                                                  | Description                                                               |
| ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- |
| `fieldId`                                                                 | *string*                                                                  | :heavy_check_mark:                                                        | UUID of the custom field.                                                 |
| `name`                                                                    | *string*                                                                  | :heavy_check_mark:                                                        | N/A                                                                       |
| `type`                                                                    | [Components\TaskType](../../Models/Components/TaskType.md)                | :heavy_check_mark:                                                        | N/A                                                                       |
| `value`                                                                   | *string*                                                                  | :heavy_check_mark:                                                        | Value serialized as a string (a JSON array for `multiselect`), or `null`. |