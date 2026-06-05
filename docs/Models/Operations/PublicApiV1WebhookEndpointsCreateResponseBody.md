# PublicApiV1WebhookEndpointsCreateResponseBody

Webhook endpoint created successfully. The `Location` header contains the canonical URL of the newly created resource.


## Fields

| Field                                                                                                         | Type                                                                                                          | Required                                                                                                      | Description                                                                                                   |
| ------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- |
| `data`                                                                                                        | [Components\WebhookEndpointWithSecret](../../Models/Components/WebhookEndpointWithSecret.md)                  | :heavy_check_mark:                                                                                            | A WebhookEndpoint returned once at creation or after secret rotation. Includes the plain-text signing secret. |