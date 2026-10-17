# NifTransferList

Cursor-paginated list of tax ID transfers (`{ data, has_more, next_cursor }`). The plaintext secret never appears here.


## Fields

| Field                                                                   | Type                                                                    | Required                                                                | Description                                                             | Example                                                                 |
| ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- |
| `data`                                                                  | array<[Components\NifTransfer](../../Models/Components/NifTransfer.md)> | :heavy_check_mark:                                                      | Page of tax ID transfers.                                               |                                                                         |
| `hasMore`                                                               | *bool*                                                                  | :heavy_check_mark:                                                      | `true` when more tax ID transfers exist beyond this page.               | false                                                                   |
| `nextCursor`                                                            | *string*                                                                | :heavy_check_mark:                                                      | Opaque cursor for the next page, or `null` when `has_more` is `false`.  | null                                                                    |