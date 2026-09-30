# EventDataTaskCommentCreated

Payload (`data`) emitted with the `task_comment.created` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                            | Type                                                             | Required                                                         | Description                                                      |
| ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- |
| `type`                                                           | *string*                                                         | :heavy_check_mark:                                               | N/A                                                              |
| `object`                                                         | [Components\TaskComment](../../Models/Components/TaskComment.md) | :heavy_check_mark:                                               | A comment of a task.                                             |
| `projectId`                                                      | *string*                                                         | :heavy_check_mark:                                               | UUID of the project of the task.                                 |