# Stats

Task statistics of the project.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `totalTasks`                                                  | *int*                                                         | :heavy_check_mark:                                            | Tasks of the project, archived ones included.                 |
| `completedTasks`                                              | *int*                                                         | :heavy_check_mark:                                            | Completed tasks.                                              |
| `completionPercentage`                                        | *int*                                                         | :heavy_check_mark:                                            | Completed tasks over total, as a whole percentage.            |
| `nextDueOn`                                                   | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Earliest due date among the open tasks, or `null`.            |