# VeriFactuRecordBlockReason

Why the remission of the record is stopped, or `null`. `MISSING_CERTIFICATE`: your company has no usable certificate (missing, expired, revoked or for another tax ID). `MISSING_REPRESENTATION`: the remission mode is a third-party one and there is no current representation of the kind it asks for. `PRESENTER_NOT_ENABLED`: AEAT says the presenter is not enabled (`4112` or `3003`). `SUBMISSION_REJECTED`: AEAT rejected the submission for a client-side fault. These four need an action from you and make `is_blocked` true. `SYSTEM_CERTIFICATE_UNAVAILABLE`: the certificate of Factuarea that remits in the third-party modes is not available; it is on our side, does NOT block (`is_blocked` stays `false`) and the record retries by itself.


## Values

| Name                           | Value                          |
| ------------------------------ | ------------------------------ |
| `MissingCertificate`           | MISSING_CERTIFICATE            |
| `MissingRepresentation`        | MISSING_REPRESENTATION         |
| `SystemCertificateUnavailable` | SYSTEM_CERTIFICATE_UNAVAILABLE |
| `PresenterNotEnabled`          | PRESENTER_NOT_ENABLED          |
| `SubmissionRejected`           | SUBMISSION_REJECTED            |