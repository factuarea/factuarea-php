# Actor

Who did it.


## Fields

| Field                                                                      | Type                                                                       | Required                                                                   | Description                                                                |
| -------------------------------------------------------------------------- | -------------------------------------------------------------------------- | -------------------------------------------------------------------------- | -------------------------------------------------------------------------- |
| `type`                                                                     | [Components\TaskActivityType](../../Models/Components/TaskActivityType.md) | :heavy_check_mark:                                                         | A member, an API key, a code forge or the system.                          |
| `id`                                                                       | *string*                                                                   | :heavy_check_mark:                                                         | UUID of the member, or `null`.                                             |
| `name`                                                                     | *string*                                                                   | :heavy_check_mark:                                                         | Display name of the actor, or `null`.                                      |