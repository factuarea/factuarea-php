# SubjectCompany

The tax ID this run acted upon, when it comes from a rule of scope `cartera`. It is `null` for runs of scope `empresa`, which act on the company that owns the rule and have no subject. `id` and `name` are themselves `null` when the company cannot be read at that moment: the block is still emitted, so a portfolio run stays distinguishable from a company-scoped one. When the tax ID is deleted, the run is kept but its link to the company is cleared, so `subject_company` becomes `null`, as in a run of scope `empresa`.


## Fields

| Field                                                                                | Type                                                                                 | Required                                                                             | Description                                                                          |
| ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| `id`                                                                                 | *string*                                                                             | :heavy_check_mark:                                                                   | Opaque identifier of the tax ID, a UUID v7, or `null` when it can no longer be read. |
| `name`                                                                               | *string*                                                                             | :heavy_check_mark:                                                                   | Name of the tax ID, or `null` when it can no longer be read.                         |