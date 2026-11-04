# ConnectionMethod

How the account was linked to Stripe: `account_links` (Stripe Connect onboarding), `stripe_app` (Stripe App) or `platform` (the own Stripe account of the company that issues the Factuarea invoices; it has no stored credential and does not accept `subscription_autoinvoicing_enabled`). Read-only.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `AccountLinks` | account_links  |
| `StripeApp`    | stripe_app     |
| `Platform`     | platform       |