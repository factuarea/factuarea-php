# PublicApiV1SeriesAssignPdfTemplateRequestBody

Omitted pdf_template_id preserves, null inherits and UUID assigns. Supply expected_version and/or If-Match from this exact visual URI. Empty strings, foreign selectors and fiscal fields return 422.


## Fields

| Field                                                                                                   | Type                                                                                                    | Required                                                                                                | Description                                                                                             |
| ------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------- |
| `pdfTemplateId`                                                                                         | *?string*                                                                                               | :heavy_minus_sign:                                                                                      | Optional saved preference: omitted preserves; null inherits. Invoice templates may support ticket only. |
| `expectedVersion`                                                                                       | *?int*                                                                                                  | :heavy_minus_sign:                                                                                      | Visual association CAS. Missing body CAS is valid only with If-Match of this exact visual resource.     |