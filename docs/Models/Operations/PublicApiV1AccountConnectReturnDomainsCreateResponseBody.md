# PublicApiV1AccountConnectReturnDomainsCreateResponseBody

The return domain was registered. There is no `Location` header: a return domain has no individual read; use the list.


## Fields

| Field                                                                                               | Type                                                                                                | Required                                                                                            | Description                                                                                         |
| --------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------- |
| `data`                                                                                              | [Components\ConnectReturnDomain](../../Models/Components/ConnectReturnDomain.md)                    | :heavy_check_mark:                                                                                  | A host your account allows as the destination of the `return_url` of Stripe Connect authorizations. |