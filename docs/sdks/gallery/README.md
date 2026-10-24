# Products.Gallery

## Overview

### Available Operations

* [publicApiV1ProductsGalleryDelete](#publicapiv1productsgallerydelete) - Remove a gallery image from a product
* [publicApiV1ProductsGalleryDownload](#publicapiv1productsgallerydownload) - Download a product gallery image binary
* [publicApiV1ProductsGalleryUpload](#publicapiv1productsgalleryupload) - Upload a gallery image to a product

## publicApiV1ProductsGalleryDelete

Delete a gallery image by its 0-based index. Remaining images shift positions to fill the gap.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.delete" method="delete" path="/companies/{company}/products/{product}/gallery/{index}" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryDeleteRequest(
    company: 'Langworth and Sons',
    product: 'Intelligent Metal Shoes',
    index: 825437,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1ProductsGalleryDeleteRequest](../../Models/Operations/PublicApiV1ProductsGalleryDeleteRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1ProductsGalleryDeleteResponse](../../Models/Operations/PublicApiV1ProductsGalleryDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsGalleryDownload

Stream the raw binary of a product gallery image by its 0-based index. Returns 404 if the index is missing or the file is not on disk.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.download" method="get" path="/companies/{company}/products/{product}/gallery/{index}/download" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->gallery->publicApiV1ProductsGalleryDownload(
    company: 'Koepp, Metz and Wilderman',
    product: 'Small Granite Pants',
    index: 952765,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `product`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the product.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `index`                                                                                                                                                                                                                                                                                                                                                                      | *int*                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Zero-based position (integer) of the image in the product `gallery`.                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProductsGalleryDownloadResponse](../../Models/Operations/PublicApiV1ProductsGalleryDownloadResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsGalleryUpload

Attach an image (jpeg, png, jpg, gif or webp; up to 3 MB) to the product gallery. Returns the updated product. Fails with 422 if the gallery limit is exceeded.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/companies/{company}/products/{product}/gallery" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryUploadRequest(
    company: 'e0dd2e1d-b946-4aab-ba8e-78694f0ad40b',
    product: 'Electronic Steel Sausages',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadProductGalleryImageRequest(
        image: new Components\Image(
            fileName: 'example.file',
            content: file_get_contents('example.file');,
        ),
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/companies/{company}/products/{product}/gallery" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryUploadRequest(
    company: 'fa5899eb-0ea6-4a32-be3a-c7bd7a8d4990',
    product: '02d8dad3-1e44-4ca7-9abf-6270d2a35324',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadProductGalleryImageRequest(
        image: new Components\Image(
            fileName: 'example.file',
            content: file_get_contents('example.file');,
        ),
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/companies/{company}/products/{product}/gallery" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryUploadRequest(
    company: '5aadbaac-ace2-4bce-ad9a-9bcf68ec8d84',
    product: 'Sleek Cotton Towels',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadProductGalleryImageRequest(
        image: new Components\Image(
            fileName: 'example.file',
            content: file_get_contents('example.file');,
        ),
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/companies/{company}/products/{product}/gallery" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryUploadRequest(
    company: 'Walter - Carter',
    product: 'Small Bronze Soap',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadProductGalleryImageRequest(
        image: new Components\Image(
            fileName: 'example.file',
            content: file_get_contents('example.file');,
        ),
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/companies/{company}/products/{product}/gallery" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryUploadRequest(
    company: 'e0dd2e1d-b946-4aab-ba8e-78694f0ad40b',
    product: '5aadbaac-ace2-4bce-ad9a-9bcf68ec8d84',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadProductGalleryImageRequest(
        image: new Components\Image(
            fileName: 'example.file',
            content: file_get_contents('example.file');,
        ),
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/companies/{company}/products/{product}/gallery" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryUploadRequest(
    company: 'Becker, Stark and Streich',
    product: 'Small Bronze Soap',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadProductGalleryImageRequest(
        image: new Components\Image(
            fileName: 'example.file',
            content: file_get_contents('example.file');,
        ),
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.gallery.upload" method="post" path="/companies/{company}/products/{product}/gallery" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsGalleryUploadRequest(
    company: '3cd25255-0df6-4200-8e5e-cf483d59d192',
    product: '8d7cedfe-ef9c-477e-90d2-78eec14e762d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadProductGalleryImageRequest(
        image: new Components\Image(
            fileName: 'example.file',
            content: file_get_contents('example.file');,
        ),
    ),
);

$response = $sdk->products->gallery->publicApiV1ProductsGalleryUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1ProductsGalleryUploadRequest](../../Models/Operations/PublicApiV1ProductsGalleryUploadRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1ProductsGalleryUploadResponse](../../Models/Operations/PublicApiV1ProductsGalleryUploadResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |