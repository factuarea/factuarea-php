# AcceptQuoteRequest

Optional body to record the acceptance of the quote: `accepted_on` (date, defaults to today) and `notes`. A quote whose `valid_until` date has already passed cannot be accepted (422 `quote_expired`).


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `acceptedOn`                                                  | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | N/A                                                           |
| `notes`                                                       | *?string*                                                     | :heavy_minus_sign:                                            | N/A                                                           |