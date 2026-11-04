# PackGalleryImage

An image of the gallery of a pack, as listed in `Pack.gallery`. The standalone resource returned by the image endpoints is `PackImage`.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `id`                                                          | *string*                                                      | :heavy_check_mark:                                            | Public identifier (UUID v7) of the image.                     |
| `url`                                                         | *string*                                                      | :heavy_check_mark:                                            | Public, permanent URL of the image (no signature, no expiry). |
| `thumbUrl`                                                    | *string*                                                      | :heavy_check_mark:                                            | URL of the 480 px thumbnail.                                  |
| `alt`                                                         | *string*                                                      | :heavy_check_mark:                                            | Alternative text.                                             |
| `width`                                                       | *int*                                                         | :heavy_check_mark:                                            | Width in pixels.                                              |
| `height`                                                      | *int*                                                         | :heavy_check_mark:                                            | Height in pixels.                                             |
| `position`                                                    | *int*                                                         | :heavy_check_mark:                                            | 1-based position in the gallery.                              |
| `contentType`                                                 | *string*                                                      | :heavy_check_mark:                                            | Every image is stored as WebP.                                |