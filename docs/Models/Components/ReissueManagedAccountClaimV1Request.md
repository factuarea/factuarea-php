# ReissueManagedAccountClaimV1Request

Optional body of the claim voucher reissue: `send_email` (`false` by default) emails the new link to the holder instead of returning the voucher in the response.


## Fields

| Field                                                                                                          | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `sendEmail`                                                                                                    | *?bool*                                                                                                        | :heavy_minus_sign:                                                                                             | Email the new claim link to the holder (`true`) or return the voucher once in the response (`false`, default). |