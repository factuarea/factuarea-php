# VeriFactuConfigEnvironment

AEAT environment your records are submitted to: `production` (the real AEAT) or `sandbox` (the AEAT pre-production test environment). It is set by the Factuarea instance, not per company: always `production` on the production API. Test mode (`fact_test_` keys) never reaches AEAT: VeriFactu operations return 403 `module_not_available_in_sandbox` there.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Production` | production   |
| `Sandbox`    | sandbox      |