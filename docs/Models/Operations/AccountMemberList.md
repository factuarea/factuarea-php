# AccountMemberList

Cursor-paginated list of account members (`{ data, has_more, next_cursor }`).


## Fields

| Field                                                                       | Type                                                                        | Required                                                                    | Description                                                                 | Example                                                                     |
| --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- |
| `data`                                                                      | array<[Components\AccountMember](../../Models/Components/AccountMember.md)> | :heavy_check_mark:                                                          | Page of account members.                                                    |                                                                             |
| `hasMore`                                                                   | *bool*                                                                      | :heavy_check_mark:                                                          | `true` when more members exist beyond this page.                            | false                                                                       |
| `nextCursor`                                                                | *string*                                                                    | :heavy_check_mark:                                                          | Opaque cursor for the next page, or `null` when `has_more` is `false`.      | null                                                                        |