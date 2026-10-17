# SendAccountWebhookTestV1Request

Queue a signed test delivery to the account webhook endpoint of the path. `type` is optional: when omitted the first subscribed event type is used; when set it must be an event type an account endpoint can subscribe to (a company event of the public catalog or an `account.*` event) AND one the endpoint subscribes to, otherwise 422.


## Fields

| Field                                                                                                              | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `type`                                                                                                             | [?Components\SendAccountWebhookTestV1RequestType](../../Models/Components/SendAccountWebhookTestV1RequestType.md)  | :heavy_minus_sign:                                                                                                 | Event type to simulate. It must be one the endpoint subscribes to; when omitted the first subscribed type is used. |