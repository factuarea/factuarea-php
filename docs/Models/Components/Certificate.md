# Certificate

The company certificate the seal was made with, kept for traceability.


## Fields

| Field                                                             | Type                                                              | Required                                                          | Description                                                       |
| ----------------------------------------------------------------- | ----------------------------------------------------------------- | ----------------------------------------------------------------- | ----------------------------------------------------------------- |
| `serialNumber`                                                    | *string*                                                          | :heavy_check_mark:                                                | Serial number of the signing certificate.                         |
| `subjectName`                                                     | *string*                                                          | :heavy_check_mark:                                                | Subject (holder) name of the signing certificate.                 |
| `thumbprint`                                                      | *string*                                                          | :heavy_check_mark:                                                | SHA-256 thumbprint of the certificate, 64 hexadecimal characters. |
| `validTo`                                                         | [\DateTime](https://www.php.net/manual/en/class.datetime.php)     | :heavy_check_mark:                                                | End of validity of the certificate (ISO 8601).                    |