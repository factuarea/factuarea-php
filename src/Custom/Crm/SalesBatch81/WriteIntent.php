<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent;
use LogicException;

/** Freeze first bytes and key before dispatch; no repeat is authorized by a failed response. */
final class WriteIntent
{
    private bool $dispatched = false;
    public readonly string $bodySha256;
    public function __construct(public readonly string $action, public readonly string $path, public readonly string $key,
        #[\SensitiveParameter] public readonly string $firstBody, private readonly string $contextFingerprint)
    {
        if (!in_array($action, ['leadCreate', 'opportunityCreate', 'activityCreate', 'leadPatch'], true)) {
            throw new LogicException('Unsupported native action.');
        }
        $fixed = ['leadCreate' => '/crm/leads', 'opportunityCreate' => '/crm/opportunity', 'activityCreate' => '/crm/crmactivity'];
        if ($action === 'leadPatch') {
            if (!str_starts_with($path, '/crm/leads/')) {
                throw new LogicException('The native Lead PATCH path is required.');
            }
            Wire::uuid(substr($path, strlen('/crm/leads/')));
        } elseif ($path !== $fixed[$action]) {
            throw new LogicException('Only the actual native owner route is admitted.');
        }
        new OriginalIntent($action === 'leadPatch' ? 'leadCreate' : $action, $key, $firstBody);
        $this->bodySha256 = hash('sha256', $firstBody);
    }
    public function claim(string $contextFingerprint): void
    {
        if (!hash_equals($this->contextFingerprint, $contextFingerprint)) {
            throw new LogicException('The intent belongs to its original SDK client and credential context.');
        }
        if ($this->dispatched) {
            throw new LogicException('The native write was already dispatched. Read its original; do not retry.');
        }
        $this->dispatched = true;
    }
    public function assertContext(string $contextFingerprint): void
    {
        if (!$this->dispatched || !hash_equals($this->contextFingerprint, $contextFingerprint)) {
            throw new LogicException('Recovery requires the original dispatched intent and native SDK context.');
        }
    }
    public function original(): OriginalIntent
    {
        if ($this->action === 'leadPatch') {
            throw new LogicException('No native original GET for Lead PATCH is declared.');
        }

        return new OriginalIntent($this->action, $this->key, $this->firstBody);
    }
}
