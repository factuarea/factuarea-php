# AcceptQuoteRequest

Public REST API v1 — POST /v1/quotes/{uuid}/accept.

Optional body: `accepted_on` (date, defaults to today), `notes`.
The controller performs the cross-field validation for `quote_expired`
(`valid_until < today` → 422).


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `acceptedOn`                                                  | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | N/A                                                           |
| `notes`                                                       | *?string*                                                     | :heavy_minus_sign:                                            | N/A                                                           |