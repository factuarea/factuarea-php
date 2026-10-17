# RemissionMode

Who submits the VeriFactu records of your company to AEAT. It does not change whether the company operates in VERI*FACTU or NO VERI*FACTU mode: it only decides whose electronic certificate signs and submits the records. `own_certificate` (default): the records are submitted with the electronic certificate of the company itself. `social_collaborator`: Factuarea submits the records on your behalf as a social collaborator, with the certificate of Factuarea; it needs an active `social_collaboration_annex_i` representation. `power_of_attorney`: Factuarea submits the records on your behalf as an attorney-in-fact, with the certificate of Factuarea; it needs an active `aeat_power_of_attorney` representation.


## Values

| Name                 | Value                |
| -------------------- | -------------------- |
| `OwnCertificate`     | own_certificate      |
| `SocialCollaborator` | social_collaborator  |
| `PowerOfAttorney`    | power_of_attorney    |