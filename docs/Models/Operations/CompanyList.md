# CompanyList

Cursor-paginated list of the tax IDs of your account (`{ data, has_more, next_cursor }`).


## Fields

| Field                                                                  | Type                                                                   | Required                                                               | Description                                                            | Example                                                                |
| ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- |
| `data`                                                                 | array<[Components\Company](../../Models/Components/Company.md)>        | :heavy_check_mark:                                                     | Page of tax IDs.                                                       |                                                                        |
| `hasMore`                                                              | *bool*                                                                 | :heavy_check_mark:                                                     | `true` when more companies exist beyond this page.                     | false                                                                  |
| `nextCursor`                                                           | *string*                                                               | :heavy_check_mark:                                                     | Opaque cursor for the next page, or `null` when `has_more` is `false`. | null                                                                   |