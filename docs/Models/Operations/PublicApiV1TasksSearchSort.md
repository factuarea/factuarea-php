# PublicApiV1TasksSearchSort

Sort order: a field for ascending or a `-` prefix for descending. Allowed fields: `created_at`, `updated_at`, `due_on`, `priority`, `number`. Defaults to `-created_at`; ties are broken by `id`, so pagination stays stable.


## Values

| Name             | Value            |
| ---------------- | ---------------- |
| `CreatedAt`      | created_at       |
| `MinusCreatedAt` | -created_at      |
| `UpdatedAt`      | updated_at       |
| `MinusUpdatedAt` | -updated_at      |
| `DueOn`          | due_on           |
| `MinusDueOn`     | -due_on          |
| `Priority`       | priority         |
| `MinusPriority`  | -priority        |
| `Number`         | number           |
| `MinusNumber`    | -number          |