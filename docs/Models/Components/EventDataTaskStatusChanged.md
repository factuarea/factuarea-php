# EventDataTaskStatusChanged

Payload (`data`) emitted with the `task.status_changed` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                                             | Type                                                                              | Required                                                                          | Description                                                                       |
| --------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- |
| `type`                                                                            | *string*                                                                          | :heavy_check_mark:                                                                | N/A                                                                               |
| `object`                                                                          | [Components\Task](../../Models/Components/Task.md)                                | :heavy_check_mark:                                                                | A task of a project board.                                                        |
| `previousStatus`                                                                  | [Components\PreviousStatus](../../Models/Components/PreviousStatus.md)            | :heavy_check_mark:                                                                | Board status before the change.                                                   |
| `previousColumnId`                                                                | *string*                                                                          | :heavy_check_mark:                                                                | Column before the change, or `null` when the task was in the backlog or archived. |