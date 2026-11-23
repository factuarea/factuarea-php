# AlternativeIdType

AEAT IDType (lista L7): nif_iva, passport, country_id, residence_certificate, other_document or not_registered. On input the legacy values `tax_id_foreign` and `national_id` are also accepted and normalized, so the response never returns them.


## Values

| Name                   | Value                  |
| ---------------------- | ---------------------- |
| `NifIva`               | nif_iva                |
| `Passport`             | passport               |
| `CountryId`            | country_id             |
| `ResidenceCertificate` | residence_certificate  |
| `OtherDocument`        | other_document         |
| `NotRegistered`        | not_registered         |