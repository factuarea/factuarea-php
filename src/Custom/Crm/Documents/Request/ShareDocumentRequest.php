<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;
final readonly class ShareDocumentRequest
{
    public function __construct(public int $expectedVersion, public string $grantType, public string $granteeId, public string|null|Omitted $expiresAt = Omitted::Value)
    {
        Wire::integer($expectedVersion);
        Wire::uuid($granteeId, $grantType === 'user');
        if (!in_array($grantType, ['user', 'team'], true)) {
            throw new InvalidArgumentException('Only user/team document grants exist.');
        }
        if (is_string($expiresAt)) {
            Wire::timestamp($expiresAt);
        }
    }
    /** @return array<string,int|string|null> */
    public function toArray(): array
    {
        $out = ['expected_version' => $this->expectedVersion, 'grant_type' => $this->grantType, 'grantee_id' => $this->granteeId];
        if (!$this->expiresAt instanceof Omitted) {
            $out['expires_at'] = $this->expiresAt;
        }

        return $out;
    }
}
