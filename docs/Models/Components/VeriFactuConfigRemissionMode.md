# VeriFactuConfigRemissionMode

Who remits your records to AEAT: `own_certificate` (the default: your own certificate), `social_collaborator` (Factuarea remits with its certificate as social collaborator, annex I of the Resolution of 18-12-2024) or `power_of_attorney` (Factuarea remits under a power of attorney registered with AEAT). A third-party mode requires an active representation of the kind it asks for (`POST /v1/verifactu/representation`); change it with `PUT /v1/verifactu/settings`.


## Values

| Name                 | Value                |
| -------------------- | -------------------- |
| `OwnCertificate`     | own_certificate      |
| `SocialCollaborator` | social_collaborator  |
| `PowerOfAttorney`    | power_of_attorney    |