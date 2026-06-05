# ClientActivityPerformedBy


## Fields

| Field                                                                          | Type                                                                           | Required                                                                       | Description                                                                    |
| ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ |
| `type`                                                                         | [Components\ClientActivityType](../../Models/Components/ClientActivityType.md) | :heavy_check_mark:                                                             | Type of actor that originated the event.                                       |
| `id`                                                                           | *string*                                                                       | :heavy_check_mark:                                                             | UUID (v7) of the user who performed the action.                                |
| `name`                                                                         | *string*                                                                       | :heavy_check_mark:                                                             | Nombre del usuario en el momento del evento.                                   |