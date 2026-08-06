# EventDataProductUpdated

Payload (`data`) emitted with the `product.updated` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                    | Type                                                     | Required                                                 | Description                                              |
| -------------------------------------------------------- | -------------------------------------------------------- | -------------------------------------------------------- | -------------------------------------------------------- |
| `type`                                                   | *string*                                                 | :heavy_check_mark:                                       | N/A                                                      |
| `object`                                                 | [Components\Product](../../Models/Components/Product.md) | :heavy_check_mark:                                       | A product in your catalog.                               |