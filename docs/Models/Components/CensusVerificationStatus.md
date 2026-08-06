# CensusVerificationStatus

Census result. `identified`: name + tax ID match an active taxpayer. `not_identified`: the pair is not in the census. `not_identified_similar`: a similar individual exists (natural persons only). `identified_inactive` / `identified_revoked`: the taxpayer is deregistered or revoked. `unavailable`: AEAT could not answer (timeout, fault, no platform certificate) — verification is informational and never blocks.


## Values

| Name                   | Value                  |
| ---------------------- | ---------------------- |
| `Identified`           | identified             |
| `NotIdentified`        | not_identified         |
| `NotIdentifiedSimilar` | not_identified_similar |
| `IdentifiedInactive`   | identified_inactive    |
| `IdentifiedRevoked`    | identified_revoked     |
| `Unavailable`          | unavailable            |