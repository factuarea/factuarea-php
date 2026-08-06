# VerificationReason

Reason of the live verification: `verified` (intact), `snapshot_mismatch` (the snapshot changed since sealing), `signature_invalid` (the signature no longer validates) or `certificate_unreadable` (the certificate could not be read).


## Values

| Name                    | Value                   |
| ----------------------- | ----------------------- |
| `Verified`              | verified                |
| `SnapshotMismatch`      | snapshot_mismatch       |
| `SignatureInvalid`      | signature_invalid       |
| `CertificateUnreadable` | certificate_unreadable  |