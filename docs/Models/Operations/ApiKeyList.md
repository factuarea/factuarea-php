# ApiKeyList

Cursor-paginated list of API keys (`{ data, has_more, next_cursor }`).


## Fields

| Field                                                                  | Type                                                                   | Required                                                               | Description                                                            | Example                                                                |
| ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- |
| `data`                                                                 | array<[Components\ApiKey](../../Models/Components/ApiKey.md)>          | :heavy_check_mark:                                                     | Page of API keys.                                                      |                                                                        |
| `hasMore`                                                              | *bool*                                                                 | :heavy_check_mark:                                                     | `true` when more keys exist beyond this page.                          | false                                                                  |
| `nextCursor`                                                           | *string*                                                               | :heavy_check_mark:                                                     | Opaque cursor for the next page, or `null` when `has_more` is `false`. | null                                                                   |