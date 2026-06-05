# PublicApiV1ProductsGalleryUploadData


## Fields

| Field                                                            | Type                                                             | Required                                                         | Description                                                      |
| ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- |
| `object`                                                         | *string*                                                         | :heavy_check_mark:                                               | N/A                                                              |
| `index`                                                          | *int*                                                            | :heavy_check_mark:                                               | Position (0-based) of the newly uploaded image in the gallery.   |
| `url`                                                            | *string*                                                         | :heavy_check_mark:                                               | Public URL of the uploaded image.                                |
| `contentType`                                                    | [Operations\ContentType](../../Models/Operations/ContentType.md) | :heavy_check_mark:                                               | MIME type de la imagen.                                          |
| `galleryTotal`                                                   | *int*                                                            | :heavy_check_mark:                                               | Total number of images in the gallery after the upload.          |