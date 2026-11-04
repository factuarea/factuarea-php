# PackImage

An image of the pack gallery, as returned by uploading or editing it. It has no read endpoint of its own: the whole gallery is in `Pack.gallery`.


## Fields

| Field                                     | Type                                      | Required                                  | Description                               |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| `id`                                      | *string*                                  | :heavy_check_mark:                        | Public identifier (UUID v7) of the image. |
| `object`                                  | *string*                                  | :heavy_check_mark:                        | N/A                                       |
| `url`                                     | *string*                                  | :heavy_check_mark:                        | Public, permanent URL of the image.       |
| `thumbUrl`                                | *string*                                  | :heavy_check_mark:                        | URL of the 480 px thumbnail.              |
| `alt`                                     | *string*                                  | :heavy_check_mark:                        | Alternative text.                         |
| `width`                                   | *int*                                     | :heavy_check_mark:                        | Width in pixels.                          |
| `height`                                  | *int*                                     | :heavy_check_mark:                        | Height in pixels.                         |
| `position`                                | *int*                                     | :heavy_check_mark:                        | 1-based position in the gallery.          |
| `contentType`                             | *string*                                  | :heavy_check_mark:                        | Every image is stored as WebP.            |