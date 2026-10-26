# EventDataClientMetadataChanged

Payload (`data`) emitted with the `client.metadata_changed` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                  | Type                                                   | Required                                               | Description                                            |
| ------------------------------------------------------ | ------------------------------------------------------ | ------------------------------------------------------ | ------------------------------------------------------ |
| `type`                                                 | *string*                                               | :heavy_check_mark:                                     | N/A                                                    |
| `object`                                               | [Components\Client](../../Models/Components/Client.md) | :heavy_check_mark:                                     | A customer of your company.                            |
| `previousKeys`                                         | array<*string*>                                        | :heavy_check_mark:                                     | N/A                                                    |
| `newKeys`                                              | array<*string*>                                        | :heavy_check_mark:                                     | N/A                                                    |