# EventDeletedObject

Degraded snapshot emitted with `*.deleted` events when the resource could no longer be recovered at emission time.


## Fields

| Field                                                | Type                                                 | Required                                             | Description                                          |
| ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- |
| `id`                                                 | *string*                                             | :heavy_check_mark:                                   | Opaque identifier (UUID v7) of the deleted resource. |
| `deleted`                                            | *bool*                                               | :heavy_check_mark:                                   | Always `true`.                                       |