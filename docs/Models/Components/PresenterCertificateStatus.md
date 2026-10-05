# PresenterCertificateStatus

State of the certificate that the remission would present given `remission_mode`: yours in `own_certificate`, the one of Factuarea in a third-party mode. `valid`: usable; `invalid`: configured but expired, revoked or for another tax ID; `not_configured`: there is none. The certificate of Factuarea never exposes its holder, tax ID, serial number or identifier.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `Valid`         | valid           |
| `Invalid`       | invalid         |
| `NotConfigured` | not_configured  |