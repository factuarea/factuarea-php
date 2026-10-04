# BusinessContactImportPreviewStatus

Persisted execution status; null for preview and dry-run. Synchronous responses can be failed with unprocessed rows after a concurrent closure.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Queued`    | queued      |
| `Running`   | running     |
| `Completed` | completed   |
| `Partial`   | partial     |
| `Failed`    | failed      |