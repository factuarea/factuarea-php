# PublicApiV1PdfTemplatesDuplicateDocumentKind

Template kind: `commercial` (invoice, quote, pro forma and delivery note) or `transport-document` (transport control document, DeCA). A template never changes kind. Omitted, the copy keeps the kind of the source; `transport-document` on a commercial source creates a new control template that copies only the design (preset, branding, typography and colors). A control template cannot be duplicated as `commercial`.


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `Commercial`        | commercial          |
| `TransportDocument` | transport-document  |