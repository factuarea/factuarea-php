<!-- Start SDK Example Usage [usage] -->
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

$body = new Components\AcceptProformaRequest(
    reason: 'Cliente confirma pedido por telefono',
);

$response = $sdk->proformas->publicApiV1ProformasAccept(
    proforma: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```
<!-- End SDK Example Usage [usage] -->