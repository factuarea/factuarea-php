# RevokeApiKeyV1Request

Optionally record why the API key is being revoked. `reason` (string, ≤500 chars) is optional; when omitted a default reason is stored for auditing.


## Fields

| Field                                                           | Type                                                            | Required                                                        | Description                                                     |
| --------------------------------------------------------------- | --------------------------------------------------------------- | --------------------------------------------------------------- | --------------------------------------------------------------- |
| `reason`                                                        | *?string*                                                       | :heavy_minus_sign:                                              | Optional reason for the revocation (recorded in the audit log). |