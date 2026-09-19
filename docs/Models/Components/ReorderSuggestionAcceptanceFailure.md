# ReorderSuggestionAcceptanceFailure

One item the acceptance could not order, identified by its 0-based POSITION in the request body — not by its catalog identifier, so a row whose item does not even resolve can still be pointed at.


## Fields

| Field                                                                            | Type                                                                             | Required                                                                         | Description                                                                      |
| -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| `index`                                                                          | *int*                                                                            | :heavy_check_mark:                                                               | 0-based position of the item within the `items` array of the request.            |
| `errorCode`                                                                      | *string*                                                                         | :heavy_check_mark:                                                               | Machine-readable error code from the v1 error catalog (stable across languages). |
| `errorMessage`                                                                   | *string*                                                                         | :heavy_check_mark:                                                               | Human-readable reason, in Spanish.                                               |