# EventDataTaskMoved

Payload (`data`) emitted with the `task.moved` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                              | Type                                               | Required                                           | Description                                        |
| -------------------------------------------------- | -------------------------------------------------- | -------------------------------------------------- | -------------------------------------------------- |
| `type`                                             | *string*                                           | :heavy_check_mark:                                 | N/A                                                |
| `object`                                           | [Components\Task](../../Models/Components/Task.md) | :heavy_check_mark:                                 | A task of a project board.                         |
| `fromProjectId`                                    | *string*                                           | :heavy_check_mark:                                 | Project the task was moved from.                   |