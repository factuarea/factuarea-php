# ~~UpdateVeriFactuSettingsV1RequestEnvironment~~

Deprecated and ignored: the AEAT environment is set by the Factuarea instance, not per company (`production`, the real AEAT, on the production API), and the response returns it in `environment`. Still accepted so existing clients keep working; it will be removed on 2026-12-07.

> :warning: **DEPRECATED**: This will be removed in a future release, please migrate away from it as soon as possible.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Sandbox`    | sandbox      |
| `Production` | production   |