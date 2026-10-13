<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\OAuthOriginals;
use UnexpectedValueException;
final readonly class CredentialContext
{
    public const OPERATION = 'crm_access_context.show';
    public const FINE_SCOPE = 'crm_access_context:read';
    public const OAUTH_SCOPE = 'crm_access_context.read';
    /** @param list<string> $scopes */
    private function __construct(public string $companyId, public string $credentialId, public string $clientId,
        public string $environment, public array $scopes, public string $authorizationVersion)
    {
    }
    public static function uuid(mixed $value): string
    {
        if (!is_string($value) || preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/Di', $value) !== 1) {
            throw new UnexpectedValueException('A native CRM UUID is required.');
        }

        return strtolower($value);
    }
    public static function fromWire(mixed $value): self
    {
        if (!is_array($value) || !in_array($value['environment'] ?? null, ['live', 'test'], true)
            || !is_string($value['authorization_version'] ?? null) || $value['authorization_version'] === ''
            || !is_array($value['scopes'] ?? null) || !array_is_list($value['scopes'])) {
            throw new UnexpectedValueException('Invalid native OAuth credential context.');
        }
        foreach ($value['scopes'] as $scope) {
            if (!is_string($scope) || ($scope !== '*' && preg_match('/^[a-z][a-z0-9_]*:[a-z][a-z0-9_]*$/D', $scope) !== 1)) {
                throw new UnexpectedValueException('Invalid native fine scope.');
            }
        }
        if (count(array_unique($value['scopes'])) !== count($value['scopes'])) {
            throw new UnexpectedValueException('Duplicate native fine scope.');
        }

        return new self(self::uuid($value['company_id'] ?? null), self::uuid($value['credential_id'] ?? null), self::uuid($value['client_id'] ?? null),
            $value['environment'], $value['scopes'], $value['authorization_version']);
    }
}
