# AccountBillingPaymentMethod

Default payment method on file, or null when none is configured.


## Fields

| Field                                   | Type                                    | Required                                | Description                             | Example                                 |
| --------------------------------------- | --------------------------------------- | --------------------------------------- | --------------------------------------- | --------------------------------------- |
| `brand`                                 | *string*                                | :heavy_check_mark:                      | Card brand (e.g. `visa`, `mastercard`). | visa                                    |
| `lastFour`                              | *string*                                | :heavy_check_mark:                      | Last four digits of the card.           | 4242                                    |