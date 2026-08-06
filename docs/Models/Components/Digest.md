# Digest

The canonical digest of the close snapshot at sealing time.


## Fields

| Field                                                                    | Type                                                                     | Required                                                                 | Description                                                              |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `algorithm`                                                              | [Components\AlgorithmSha256](../../Models/Components/AlgorithmSha256.md) | :heavy_check_mark:                                                       | Digest algorithm (always `sha256`).                                      |
| `value`                                                                  | *string*                                                                 | :heavy_check_mark:                                                       | The snapshot digest, 64 uppercase hexadecimal characters.                |