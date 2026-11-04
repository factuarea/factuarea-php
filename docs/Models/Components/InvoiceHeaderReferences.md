# InvoiceHeaderReferences

Exactly three supported header keys. On update, omitted keys preserve their values, an empty object does nothing and null clears the whole object. Dates and issuer references are line-only.


## Fields

| Field                                                                                      | Type                                                                                       | Required                                                                                   | Description                                                                                |
| ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ |
| `receiverContractReference`                                                                | *?string*                                                                                  | :heavy_minus_sign:                                                                         | At most 20 Unicode characters. Null clears the reference; whitespace-only text is invalid. |
| `receiverTransactionReference`                                                             | *?string*                                                                                  | :heavy_minus_sign:                                                                         | At most 20 Unicode characters. Null clears the reference; whitespace-only text is invalid. |
| `fileReference`                                                                            | *?string*                                                                                  | :heavy_minus_sign:                                                                         | At most 20 Unicode characters. Null clears the reference; whitespace-only text is invalid. |