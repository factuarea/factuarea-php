# UploadProductGalleryImageRequest

Upload an image to the product gallery as `multipart/form-data`: the `photo` field (`image` is accepted as an alias) — jpeg, png, jpg, gif or webp, max 3 MB.


## Fields

| Field                                                | Type                                                 | Required                                             | Description                                          |
| ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- |
| `image`                                              | [Components\Image](../../Models/Components/Image.md) | :heavy_check_mark:                                   | Maximum file size: 3072 kilobytes.                   |