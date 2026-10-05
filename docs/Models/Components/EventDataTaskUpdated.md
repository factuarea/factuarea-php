# EventDataTaskUpdated

Payload (`data`) emitted with the `task.updated` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                                     | Type                                                                      | Required                                                                  | Description                                                               |
| ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- |
| `type`                                                                    | *string*                                                                  | :heavy_check_mark:                                                        | N/A                                                                       |
| `object`                                                                  | [Components\Task](../../Models/Components/Task.md)                        | :heavy_check_mark:                                                        | A task of a project board.                                                |
| `changedFields`                                                           | array<[Components\ChangedField](../../Models/Components/ChangedField.md)> | :heavy_check_mark:                                                        | Fields of the public `Task` resource that changed.                        |