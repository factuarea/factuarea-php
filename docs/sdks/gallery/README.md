# Products.Gallery

## Overview

### Available Operations

* [publicApiV1ProductsGalleryDelete](#publicapiv1productsgallerydelete) - Remove a gallery image from a product
* [publicApiV1ProductsGalleryDownload](#publicapiv1productsgallerydownload) - Download a product gallery image binary
* [publicApiV1ProductsGalleryUpload](#publicapiv1productsgalleryupload) - Upload a gallery image to a product

## publicApiV1ProductsGalleryDelete

Delete a gallery image by its 0-based index. Remaining images shift positions to fill the gap.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.delete" method="delete" path="/products/{product}/gallery/{index}" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->gallery->publicApiV1ProductsGalleryDelete(
    product: 'Intelligent Metal Shoes',
    index: 825437,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `product`                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `index`                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | *int*                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsGalleryDeleteResponse](../../Models/Operations/PublicApiV1ProductsGalleryDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsGalleryDownload

Stream the raw binary of a product gallery image by its 0-based index. Returns 404 if the index is missing or the file is not on disk.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.download" method="get" path="/products/{product}/gallery/{index}/download" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->gallery->publicApiV1ProductsGalleryDownload(
    product: 'Small Granite Pants',
    index: 952765

);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `product`          | *string*           | :heavy_check_mark: | N/A                |
| `index`            | *int*              | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1ProductsGalleryDownloadResponse](../../Models/Operations/PublicApiV1ProductsGalleryDownloadResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsGalleryUpload

Attach an image (jpeg, png, jpg, gif or webp; up to 3 MB) to the product gallery. Returns the updated product. Fails with 422 if the gallery limit is exceeded.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/products/{product}/gallery" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UploadProductGalleryImageRequest(
    image: new Components\Image(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    product: 'Electronic Steel Sausages',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/products/{product}/gallery" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UploadProductGalleryImageRequest(
    image: new Components\Image(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    product: 'Sleek Cotton Towels',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/products/{product}/gallery" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UploadProductGalleryImageRequest(
    image: new Components\Image(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    product: 'Small Bronze Soap',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `product`                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\UploadProductGalleryImageRequest](../../Models/Components/UploadProductGalleryImageRequest.md)                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsGalleryUploadResponse](../../Models/Operations/PublicApiV1ProductsGalleryUploadResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |