# Publication

Exact publication intent for the current presentation/CAS. `public_url` is null while queued and is populated only for the exact ready, publicly shared, authorized version. Always returned (possibly `null`) by the `public-link` sub-resource; the `public_link` embedded in an invoice omits it.


## Fields

| Field                                                                    | Type                                                                     | Required                                                                 | Description                                                              |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `publicationId`                                                          | *string*                                                                 | :heavy_check_mark:                                                       | N/A                                                                      |
| `state`                                                                  | [Components\PublicLinkState](../../Models/Components/PublicLinkState.md) | :heavy_check_mark:                                                       | N/A                                                                      |
| `publicUrl`                                                              | *string*                                                                 | :heavy_check_mark:                                                       | N/A                                                                      |