# PublicApiV1AccountWebhooksCreateResponseBody

Account webhook endpoint created. The `Location` header contains the canonical URL of the new endpoint; the body carries its signing secret once.


## Fields

| Field                                                                                                                      | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `data`                                                                                                                     | [Components\AccountWebhookWithSecret](../../Models/Components/AccountWebhookWithSecret.md)                                 | :heavy_check_mark:                                                                                                         | An `AccountWebhook` returned once at creation or after secret rotation: the same fields PLUS the plaintext signing secret. |