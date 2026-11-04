# PackCover

Cover image of the pack, resolved by the server: the first image of its gallery or, when it has none, the image of its first component that has one.


## Fields

| Field                                                                               | Type                                                                                | Required                                                                            | Description                                                                         |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `url`                                                                               | *string*                                                                            | :heavy_check_mark:                                                                  | Public, permanent URL of the image.                                                 |
| `thumbUrl`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Thumbnail URL. `null` for an image inherited from a component, which has none.      |
| `alt`                                                                               | *string*                                                                            | :heavy_check_mark:                                                                  | Alternative text. For an inherited image it is the name of the component.           |
| `source`                                                                            | [Components\PackCoverSource](../../Models/Components/PackCoverSource.md)            | :heavy_check_mark:                                                                  | `own` when it comes from the gallery of the pack, `component` when it is inherited. |