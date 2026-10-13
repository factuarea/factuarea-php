<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\OAuthOriginals;
use InvalidArgumentException;
/** Exact first HTTP wire bytes, never reconstructed from a decoded JSON object. */
final readonly class OriginalIntent
{
    public string $originalBodySha256;
    public string $path;
    public const SCOPES = [
        'leadCreate' => ['operation' => 'crm_leads.create', 'fine' => 'crm_leads:write', 'oauth' => 'crm_leads.write'],
        'opportunityCreate' => ['operation' => 'crm_opportunities.create', 'fine' => 'crm_opportunities:write', 'oauth' => 'crm_opportunities.write'],
        'opportunityStage' => ['operation' => 'crm_opportunities.move_stage', 'fine' => 'crm_opportunities:write', 'oauth' => 'crm_opportunities.write'],
        'activityCreate' => ['operation' => 'crm_activities.create', 'fine' => 'crm_activities:write', 'oauth' => 'crm_activities.write'],
    ];
    public function __construct(public string $operation, public string $idempotencyKey, public string $firstBody, ?string $opportunity = null)
    {
        $paths = ['leadCreate' => '/crm/leads/create/original', 'opportunityCreate' => '/crm/opportunity/create/original',
            'opportunityStage' => '/crm/opportunity/{opportunity}/stage/original', 'activityCreate' => '/crm/crmactivity/create/original'];
        if (!isset($paths[$operation]) || preg_match('/^[\x20-\x7e]{1,255}$/D', $idempotencyKey) !== 1 || trim($idempotencyKey) !== $idempotencyKey) {
            throw new InvalidArgumentException('A supported operation and exact printable Idempotency-Key without HTTP boundary whitespace are required.');
        }
        if (preg_match('//u', $firstBody) !== 1 || !is_object(json_decode($firstBody, false, 64, JSON_THROW_ON_ERROR))) {
            throw new InvalidArgumentException('Retain the exact first UTF-8 JSON object body.');
        }
        if (($operation === 'opportunityStage') !== ($opportunity !== null)) {
            throw new InvalidArgumentException('Only stage recovery requires an opportunity UUID.');
        }
        $this->path = $opportunity === null ? $paths[$operation] : str_replace('{opportunity}', CredentialContext::uuid($opportunity), $paths[$operation]);
        $this->originalBodySha256 = hash('sha256', $firstBody);
    }
}
