# BulkCreateResultFailure

A row that could not be created, identified by its 0-based `index`.


## Fields

| Field                                                                            | Type                                                                             | Required                                                                         | Description                                                                      |
| -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| `index`                                                                          | *int*                                                                            | :heavy_check_mark:                                                               | 0-based position of the row within the batch.                                    |
| `errorCode`                                                                      | *string*                                                                         | :heavy_check_mark:                                                               | Machine-readable error code from the v1 error catalog (stable across languages). |
| `errorMessage`                                                                   | *string*                                                                         | :heavy_check_mark:                                                               | Human-readable reason for the failure, in Spanish.                               |
| `errors`                                                                         | array<[Components\FieldIssue](../../Models/Components/FieldIssue.md)>            | :heavy_minus_sign:                                                               | Per-field blocking issues that made the row invalid.                             |