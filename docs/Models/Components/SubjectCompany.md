# SubjectCompany

The managed client company this run acted upon, when it comes from a rule of scope `cartera`. It is `null` for runs of scope `empresa`, which act on the company that owns the rule and have no subject. `id` and `name` are themselves `null` when the company can no longer be read (it was deleted): the block is still emitted, so a portfolio run stays distinguishable from a company-scoped one.


## Fields

| Field                                                                                         | Type                                                                                          | Required                                                                                      | Description                                                                                   |
| --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- |
| `id`                                                                                          | *string*                                                                                      | :heavy_check_mark:                                                                            | Opaque identifier of the managed company, a UUID v7, or `null` when it can no longer be read. |
| `name`                                                                                        | *string*                                                                                      | :heavy_check_mark:                                                                            | Name of the managed company, or `null` when it can no longer be read.                         |