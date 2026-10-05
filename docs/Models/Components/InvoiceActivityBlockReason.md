# InvoiceActivityBlockReason

`verifactu.transmission_blocked`: why the submission is stopped. It does not go out again until the cause is fixed and the record is reactivated (`POST /v1/verifactu/records/retry-blocked`).


## Values

| Name                    | Value                   |
| ----------------------- | ----------------------- |
| `MissingCertificate`    | MISSING_CERTIFICATE     |
| `MissingRepresentation` | MISSING_REPRESENTATION  |
| `PresenterNotEnabled`   | PRESENTER_NOT_ENABLED   |
| `SubmissionRejected`    | SUBMISSION_REJECTED     |