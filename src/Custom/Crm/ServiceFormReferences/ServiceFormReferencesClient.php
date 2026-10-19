<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceFormReferences;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Transport;

/** Closed GET through the SAME canonical SDK security/hooks/errors and configured GET retry policy. */
final readonly class ServiceFormReferencesClient
{
    /** @param array<string,string> $headers */
    public function __construct(private Transport $transport, private RequiredPaths $paths, private array $headers = [])
    {
        foreach ($headers as $name => $value) {
            if (!in_array($name, ['X-Active-Profile', 'Factuarea-Version'], true)) {
                throw new \InvalidArgumentException('Only native profile/version overrides are supported.');
            }
            self::header($value);
        }
    }
    public function withHeaders(?string $xActiveProfile = null, ?string $factuareaVersion = null): self
    {
        $headers = $this->headers;
        foreach (['X-Active-Profile' => $xActiveProfile, 'Factuarea-Version' => $factuareaVersion] as $name => $value) {
            if ($value !== null) {
                self::header($value);
                $headers[$name] = $value;
            }
        }

        return new self($this->transport, $this->paths, $headers);
    }
    /** Every request obtains current native collections/C7. Knowing an option identity conveys no write authority.
     * @return CrmResponse<ReferencesResult> */
    public function getServiceFormReferences(): CrmResponse
    {
        $response = $this->transport->request('GET', $this->paths->getServiceFormReferences, 'crm_capture_forms.references',
            ReferencesResult::class, [], null, null, false, $this->headers);
        if ($response->statusCode() !== 200) {
            throw new \UnexpectedValueException('Native service-form references requires HTTP 200.');
        }

        return $response;
    }
    private static function header(string $value): void
    {
        if ($value === '' || preg_match('/[\x00-\x1f\x7f]/', $value) === 1) {
            throw new \InvalidArgumentException('Invalid native header value.');
        }
    }
}
