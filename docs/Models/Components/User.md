# User

A member of your company: someone tasks can be assigned to.


## Fields

| Field                                                          | Type                                                           | Required                                                       | Description                                                    | Example                                                        |
| -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- |
| `id`                                                           | *string*                                                       | :heavy_check_mark:                                             | UUID of the user.                                              | 0193a4f2-6b1c-7d3e-8a41-2c5e7f9a1b01                           |
| `object`                                                       | [Components\UserObject](../../Models/Components/UserObject.md) | :heavy_check_mark:                                             | Stripe-like discriminator. Always `user` for this resource.    | user                                                           |
| `name`                                                         | *string*                                                       | :heavy_check_mark:                                             | N/A                                                            | Ana Pérez                                                      |
| `email`                                                        | *string*                                                       | :heavy_check_mark:                                             | N/A                                                            | ana.perez@example.com                                          |
| `initials`                                                     | *string*                                                       | :heavy_check_mark:                                             | Initials shown in the avatar.                                  | AP                                                             |
| `avatarColor`                                                  | *string*                                                       | :heavy_check_mark:                                             | Avatar color, stable per user.                                 | pink                                                           |