# UpdateQuotePublicLinkRequest

Public REST API v1 — PUT /v1/quotes/{uuid}/public-link.

`SchemaName` disambiguates the OpenAPI schema: Quote and Proforma declare
structurally identical request bodies, so without a unique name they would
collide in `components.schemas`.


## Fields

| Field                                                                                                          | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `action`                                                                                                       | [Components\UpdateQuotePublicLinkRequestAction](../../Models/Components/UpdateQuotePublicLinkRequestAction.md) | :heavy_check_mark:                                                                                             | N/A                                                                                                            |
| `extendDays`                                                                                                   | *?int*                                                                                                         | :heavy_minus_sign:                                                                                             | N/A                                                                                                            |