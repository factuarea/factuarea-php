# SendTestEventRequest

Trigger a test delivery to the webhook endpoint. `type` is optional: when omitted the endpoint first subscribed event is used; when set it must belong to the closed event catalog and be one of the endpoint subscribed events (otherwise 422).


## Fields

| Field                                                                                       | Type                                                                                        | Required                                                                                    | Description                                                                                 |
| ------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- |
| `type`                                                                                      | [?Components\SendTestEventRequestType](../../Models/Components/SendTestEventRequestType.md) | :heavy_minus_sign:                                                                          | N/A                                                                                         |