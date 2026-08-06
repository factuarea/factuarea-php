# EventDataQuoteCreated

Payload (`data`) emitted with the `quote.created` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                | Type                                                 | Required                                             | Description                                          |
| ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- |
| `type`                                               | *string*                                             | :heavy_check_mark:                                   | N/A                                                  |
| `object`                                             | [Components\Quote](../../Models/Components/Quote.md) | :heavy_check_mark:                                   | A sales quote that can be converted to an invoice.   |