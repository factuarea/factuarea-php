# PublicApiV1ContactsListBusinessContactList

Cursor-paginated list envelope shared by every v1 listing endpoint. `data` holds the page items; `has_more` signals whether another page exists; `next_cursor` is the cursor to pass back for the following page. Treat it as strictly opaque: send it through verbatim, never parse it and never assume a format — it is not the same across listings, and each listing documents its own cursor parameter. Concrete listings narrow `data` to their resource type via `allOf`.


## Fields

| Field                                                                           | Type                                                                            | Required                                                                        | Description                                                                     |
| ------------------------------------------------------------------------------- | ------------------------------------------------------------------------------- | ------------------------------------------------------------------------------- | ------------------------------------------------------------------------------- |
| `data`                                                                          | array<[Components\BusinessContact](../../Models/Components/BusinessContact.md)> | :heavy_check_mark:                                                              | N/A                                                                             |
| `hasMore`                                                                       | *bool*                                                                          | :heavy_check_mark:                                                              | N/A                                                                             |
| `nextCursor`                                                                    | *string*                                                                        | :heavy_check_mark:                                                              | N/A                                                                             |