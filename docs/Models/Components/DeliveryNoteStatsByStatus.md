# DeliveryNoteStatsByStatus


## Fields

| Field                                                                               | Type                                                                                | Required                                                                            | Description                                                                         |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `status`                                                                            | *string*                                                                            | :heavy_check_mark:                                                                  | Internal state of the delivery note.                                                |
| `count`                                                                             | *int*                                                                               | :heavy_check_mark:                                                                  | Number of delivery notes in that state.                                             |
| `total`                                                                             | *string*                                                                            | :heavy_check_mark:                                                                  | Aggregate amount of the delivery notes in that status (EUR), as a 2-decimal string. |