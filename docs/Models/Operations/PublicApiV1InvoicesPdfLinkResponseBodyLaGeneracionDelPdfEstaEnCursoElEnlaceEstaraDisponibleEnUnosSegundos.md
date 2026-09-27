# PublicApiV1InvoicesPdfLinkResponseBodyLaGeneracionDelPdfEstaEnCursoElEnlaceEstaraDisponibleEnUnosSegundos

The PDF has not been generated yet: its generation is enqueued and the response reports the `pendiente` status and a signed `pdf_url` that answers 404 until the PDF is ready. Retry shortly to obtain the link (200).


## Fields

| Field                                                                | Type                                                                 | Required                                                             | Description                                                          |
| -------------------------------------------------------------------- | -------------------------------------------------------------------- | -------------------------------------------------------------------- | -------------------------------------------------------------------- |
| `data`                                                               | [Operations\DataPendiente](../../Models/Operations/DataPendiente.md) | :heavy_check_mark:                                                   | N/A                                                                  |
| `message`                                                            | *string*                                                             | :heavy_check_mark:                                                   | N/A                                                                  |