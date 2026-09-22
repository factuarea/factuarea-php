# AcceptAccountClaimTokenV1Request

Body of the claim token redemption. It carries the plaintext `secret` exactly as the issuance response returned it, and nothing else. There is NO `company_id` here and it is not an omission: the tax ID is named by the TOKEN, not by whoever presents it, so accepting one would allow aiming a token at a tax ID it does not cover — the request rejects it explicitly instead of ignoring it in silence. The secret travels from the body to the command and dies there: it is never logged, never recorded in any audit metadata, and never returned in the response, not even in the one that spends it.


## Fields

| Field                                                                              | Type                                                                               | Required                                                                           | Description                                                                        |
| ---------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------- |
| `secret`                                                                           | *string*                                                                           | :heavy_check_mark:                                                                 | Plaintext secret of the claim token, exactly as its issuance response returned it. |