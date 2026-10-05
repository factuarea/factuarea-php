# ConnectReturnDomain

A host your account allows as the destination of the `return_url` of Stripe Connect authorizations.


## Fields

| Field                                                                    | Type                                                                     | Required                                                                 | Description                                                              | Example                                                                  |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `id`                                                                     | *string*                                                                 | :heavy_check_mark:                                                       | Opaque identifier (UUID v7) of the return domain.                        | 01993c4f-52b8-7c16-a3d9-4e7b1f2c8a30                                     |
| `object`                                                                 | *string*                                                                 | :heavy_check_mark:                                                       | Always `connect_return_domain`.                                          | connect_return_domain                                                    |
| `domain`                                                                 | *string*                                                                 | :heavy_check_mark:                                                       | The host, lower-cased and normalized: no scheme, port, path or wildcard. | portal.example.com                                                       |
| `createdAt`                                                              | [\DateTime](https://www.php.net/manual/en/class.datetime.php)            | :heavy_check_mark:                                                       | Instant the domain was registered.                                       | 2026-10-05T10:15:00+00:00                                                |