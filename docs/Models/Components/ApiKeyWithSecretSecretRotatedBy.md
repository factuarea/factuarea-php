# ApiKeyWithSecretSecretRotatedBy

The person who last rotated the secret. Always `null` when the rotation was made with an API credential or the MCP server instead of a person.


## Fields

| Field                                      | Type                                       | Required                                   | Description                                | Example                                    |
| ------------------------------------------ | ------------------------------------------ | ------------------------------------------ | ------------------------------------------ | ------------------------------------------ |
| `id`                                       | *string*                                   | :heavy_check_mark:                         | Opaque identifier (UUID v7) of the person. | 01931b3e-7c4a-7f2e-9a8b-4d6e7f8a9b11       |
| `name`                                     | *string*                                   | :heavy_check_mark:                         | Full name of the person.                   | Marta Ibáñez                               |