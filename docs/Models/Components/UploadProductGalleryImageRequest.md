# UploadProductGalleryImageRequest

Public REST API v1 — POST /v1/products/{uuid}/gallery.

Multipart upload: `photo` or `image` (alias) field — jpeg/png/jpg/gif/webp,
max 3 MB.

We accept `image` as an alias of the canonical `photo` field for
forgiveness with integrators that send it following the more intuitive
convention. The controller normalizes it to `photo`.


## Fields

| Field                                                | Type                                                 | Required                                             | Description                                          |
| ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- |
| `image`                                              | [Components\Image](../../Models/Components/Image.md) | :heavy_check_mark:                                   | N/A                                                  |