# Packs.Images

## Overview

### Available Operations

* [publicApiV1PacksImagesDelete](#publicapiv1packsimagesdelete) - Remove a pack image
* [publicApiV1PacksImagesUpdate](#publicapiv1packsimagesupdate) - Update a pack image
* [publicApiV1PacksImagesUpload](#publicapiv1packsimagesupload) - Upload an image to a pack

## publicApiV1PacksImagesDelete

Delete an image from the gallery of a pack and release its storage. Returns 204 with no body.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.packs.images.delete" method="delete" path="/companies/{company}/packs/{pack}/images/{image}" -->
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

$request = new Operations\PublicApiV1PacksImagesDeleteRequest(
    company: '42750704-e789-49a1-b6f3-98bebdd43888',
    pack: '87ad4b5a-9918-4121-bfde-875a3ec68246',
    image: '46846906-3019-48d5-b17d-6cbce9ae35ce',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->packs->images->publicApiV1PacksImagesDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1PacksImagesDeleteRequest](../../Models/Operations/PublicApiV1PacksImagesDeleteRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1PacksImagesDeleteResponse](../../Models/Operations/PublicApiV1PacksImagesDeleteResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PacksImagesUpdate

Change the `alt` text or the `position` of a pack gallery image. The other images shift to keep the order contiguous.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.packs.images.update" method="patch" path="/companies/{company}/packs/{pack}/images/{image}" example="success" -->
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

$request = new Operations\PublicApiV1PacksImagesUpdateRequest(
    company: '4d567066-8321-43c3-b8d5-7099401b6542',
    pack: '91ea889f-5254-4551-9795-17b3395b693f',
    image: '7cd0cabf-8ee2-4470-9ecf-d10cd7f20263',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->packs->images->publicApiV1PacksImagesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1PacksImagesUpdateRequest](../../Models/Operations/PublicApiV1PacksImagesUpdateRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1PacksImagesUpdateResponse](../../Models/Operations/PublicApiV1PacksImagesUpdateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PacksImagesUpload

Add an image to the gallery of a pack. Send `multipart/form-data` with the file in `photo` or `image` (jpeg, png, gif or webp).
- Returns 201 with the new image and no `Location` header.
- Returns 422 `pack_image_limit_reached` when the gallery is full.
- An `Idempotency-Key` is required.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.packs.images.upload" method="post" path="/companies/{company}/packs/{pack}/images" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PacksImagesUploadRequest(
    company: '1a9a3ca7-6929-45f2-ab24-0867a7aed73a',
    pack: '1cd7b2d2-1b88-4c71-b853-2adcdc970fc6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadPackImageV1Request(),
);

$response = $sdk->packs->images->publicApiV1PacksImagesUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.packs.images.upload" method="post" path="/companies/{company}/packs/{pack}/images" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PacksImagesUploadRequest(
    company: '1ed0ecd5-1a09-484c-bb56-c7526a51b720',
    pack: 'f4fb73b6-14fc-44a1-a791-cdab65f7e887',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadPackImageV1Request(),
);

$response = $sdk->packs->images->publicApiV1PacksImagesUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.packs.images.upload" method="post" path="/companies/{company}/packs/{pack}/images" example="success" -->
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

$request = new Operations\PublicApiV1PacksImagesUploadRequest(
    company: '586ac434-7872-4292-b993-dbc13a6a51bc',
    pack: '6e9ce37b-563d-4af6-b656-4b33594e6bb1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadPackImageV1Request(),
);

$response = $sdk->packs->images->publicApiV1PacksImagesUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.packs.images.upload" method="post" path="/companies/{company}/packs/{pack}/images" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PacksImagesUploadRequest(
    company: 'eb2c0fb3-7b24-4592-b436-9ccd38c10f11',
    pack: 'eef75578-a8fc-477c-9ece-ad166a4e5814',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UploadPackImageV1Request(),
);

$response = $sdk->packs->images->publicApiV1PacksImagesUpload(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1PacksImagesUploadRequest](../../Models/Operations/PublicApiV1PacksImagesUploadRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1PacksImagesUploadResponse](../../Models/Operations/PublicApiV1PacksImagesUploadResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |