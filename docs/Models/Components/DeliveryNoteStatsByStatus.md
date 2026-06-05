# DeliveryNoteStatsByStatus


## Fields

| Field                                                   | Type                                                    | Required                                                | Description                                             |
| ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- |
| `status`                                                | *string*                                                | :heavy_check_mark:                                      | Internal state of the delivery note.                    |
| `count`                                                 | *int*                                                   | :heavy_check_mark:                                      | Number of delivery notes in that state.                 |
| `total`                                                 | *float*                                                 | :heavy_check_mark:                                      | Importe acumulado de los albaranes en ese estado (EUR). |