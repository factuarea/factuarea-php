# EmailDeliveryIndicators

Email summary of a batch of documents (`{ data }`). It does NOT paginate: you asked for a bounded batch (up to 100 ids) and you get one entry per document that HAS emails. Documents without any email, and ids that do not belong to a document of your company, are OMITTED — never returned with zeros — so `data` can be shorter than the batch you sent, or empty.


## Fields

| Field                                                                                         | Type                                                                                          | Required                                                                                      | Description                                                                                   |
| --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- |
| `data`                                                                                        | array<[Components\EmailDeliveryIndicator](../../Models/Components/EmailDeliveryIndicator.md)> | :heavy_check_mark:                                                                            | One entry per requested document that has at least one email.                                 |