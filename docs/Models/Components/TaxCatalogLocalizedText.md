# TaxCatalogLocalizedText

A catalog text in the three languages the API supports. All three are always present: the negotiated `Accept-Language` picks the primary language of the document, it never filters the payload, so a client rendering a multilingual selector does not need three requests.


## Fields

| Field              | Type               | Required           | Description        | Example            |
| ------------------ | ------------------ | ------------------ | ------------------ | ------------------ |
| `es`               | *string*           | :heavy_check_mark: | Spanish wording.   | Régimen general    |
| `en`               | *string*           | :heavy_check_mark: | English wording.   | General regime     |
| `ca`               | *string*           | :heavy_check_mark: | Catalan wording.   | Règim general      |