# PdfTemplateConfigurationChangesNoticesPlacement

Schema_version 2 only. Where the legal mentions and the data protection text are printed: `after_content` (default) right after the lines and totals, `page_bottom` anchored to the bottom of the last page of the document, before the general terms annex when they are on their own page. When the anchored block would exceed about 60 mm it is printed after the content instead and the editor preview reports `legal_notices_not_anchored`; the ticket never anchors. The footer and register texts keep following `mode`. Available on every plan.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `AfterContent` | after_content  |
| `PageBottom`   | page_bottom    |