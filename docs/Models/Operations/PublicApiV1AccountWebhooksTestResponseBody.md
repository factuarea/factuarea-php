# PublicApiV1AccountWebhooksTestResponseBody

The test delivery was queued. `data.id` is the delivery to read in the deliveries of the endpoint and `data.event_id` the id of the synthetic event it carries, marked `test: true` in the signed envelope. The event is not stored in any event log; the delivery is.


## Fields

| Field                                                                                                          | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `data`                                                                                                         | [Operations\PublicApiV1AccountWebhooksTestData](../../Models/Operations/PublicApiV1AccountWebhooksTestData.md) | :heavy_check_mark:                                                                                             | N/A                                                                                                            |