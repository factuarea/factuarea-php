# PublicApiV1AutomationsRunsStepsReplayData


## Fields

| Field                                                                    | Type                                                                     | Required                                                                 | Description                                                              |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `automationRunId`                                                        | *string*                                                                 | :heavy_check_mark:                                                       | ID of the run the rearmed step belongs to (UUID v7).                     |
| `stepIndex`                                                              | *int*                                                                    | :heavy_check_mark:                                                       | Index of the rearmed step within its run; it is the identity you replay. |