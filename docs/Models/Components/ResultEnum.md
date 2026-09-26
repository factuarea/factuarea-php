# ResultEnum

pending while the attachments are still being ingested; parked when the sender is not allowlisted or fails SPF/DKIM/DMARC authentication (no scan is created).


## Values

| Name                      | Value                     |
| ------------------------- | ------------------------- |
| `Pending`                 | pending                   |
| `Processed`               | processed                 |
| `PartiallyProcessed`      | partially_processed       |
| `NoCompatibleAttachments` | no_compatible_attachments |
| `Parked`                  | parked                    |
| `Rejected`                | rejected                  |
| `Failed`                  | failed                    |