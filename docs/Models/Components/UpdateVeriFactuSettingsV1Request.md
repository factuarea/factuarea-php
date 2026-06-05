# UpdateVeriFactuSettingsV1Request

Public REST API v1 — PUT /v1/verifactu/settings.

PARTIAL update of the company's VeriFactu configuration: each field is
optional and is only applied if present in the body (`*Provided` pattern
of `UpdateVeriFactuSettingsV1Command`). The controller resolves
the `*Provided` flag via `$this->has('field')`.

Per-field validation:
 - `enabled: boolean`
 - `mode: in:verifactu,no_verifactu`
 - `auto_transmit: boolean`
 - `environment: in:sandbox,production`
 - `notification_emails: array|max:5`, `notification_emails.*: email`


## Fields

| Field                                                             | Type                                                              | Required                                                          | Description                                                       |
| ----------------------------------------------------------------- | ----------------------------------------------------------------- | ----------------------------------------------------------------- | ----------------------------------------------------------------- |
| `enabled`                                                         | *?bool*                                                           | :heavy_minus_sign:                                                | N/A                                                               |
| `mode`                                                            | [?Components\Mode](../../Models/Components/Mode.md)               | :heavy_minus_sign:                                                | N/A                                                               |
| `autoTransmit`                                                    | *?bool*                                                           | :heavy_minus_sign:                                                | N/A                                                               |
| `environment`                                                     | [?Components\Environment](../../Models/Components/Environment.md) | :heavy_minus_sign:                                                | N/A                                                               |
| `notificationEmails`                                              | array<*string*>                                                   | :heavy_minus_sign:                                                | N/A                                                               |