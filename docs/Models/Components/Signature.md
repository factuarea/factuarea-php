# Signature

The detached digital signature of the digest.


## Fields

| Field                                                                          | Type                                                                           | Required                                                                       | Description                                                                    |
| ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ |
| `algorithm`                                                                    | [Components\AlgorithmRsaSha256](../../Models/Components/AlgorithmRsaSha256.md) | :heavy_check_mark:                                                             | Signature algorithm (always `RSA-SHA256`).                                     |
| `value`                                                                        | *string*                                                                       | :heavy_check_mark:                                                             | The signature of the digest, base64-encoded.                                   |