# RevokeNifTransferV1Request

Optional body of the tax ID transfer revocation. `reason` (string, up to 500 characters) is optional and is recorded in the audit trail; everything that decides the operation — which transfer, which account — comes from the path and the credential.


## Fields

| Field                                                                                 | Type                                                                                  | Required                                                                              | Description                                                                           |
| ------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------- |
| `reason`                                                                              | *?string*                                                                             | :heavy_minus_sign:                                                                    | Optional reason for the revocation (up to 500 characters, recorded in the audit log). |