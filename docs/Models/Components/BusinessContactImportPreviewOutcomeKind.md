# BusinessContactImportPreviewOutcomeKind

Business reading of a finished execution, same as the tracking resource; null for preview, dry-run and queued imports. `nothing_changed` means nothing was created or updated; do not present it as a success.


## Values

| Name             | Value            |
| ---------------- | ---------------- |
| `ChangesApplied` | changes_applied  |
| `NothingChanged` | nothing_changed  |
| `Partial`        | partial          |
| `Failed`         | failed           |