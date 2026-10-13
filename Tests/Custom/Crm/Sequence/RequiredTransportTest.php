<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\Sequence\RequiredPaths;
use PHPUnit\Framework\TestCase;

final class RequiredTransportTest extends TestCase
{
    public function test_paths_require_exact_native_parameters_and_cannot_escape_configured_v1_base(): void
    {
        // Explicit fixture paths do not advertise or activate any production route.
        $paths = ['crm/crmsequence', 'crm/crmsequence/{sequence}/preview', 'crm/crmsequence', 'crm/crmsequence/{sequence}',
            'crm/crmsequence/{sequence}/enrolments', 'crm/crmsequence/original', 'crm/crmsequence/enrolments/original',
            'crm/crmsequence/enrolments/{enrolment}/pause', 'crm/crmsequence/enrolments/{enrolment}/resume', 'crm/crmsequence/enrolments/{enrolment}/cancel'];
        $native = new RequiredPaths(...$paths);
        $id = '019a4afd-5000-7000-8000-000000000001';
        self::assertSame('crm/crmsequence/enrolments/'.$id.'/pause', $native->target('pause', $id));
        foreach (['https://another-host/crm', '../crm', 'crm/%2e%2e/escape', '/v1/crm', 'crm?token=foreign', 'crm/{mailbox}/preview', 'crm/{sequence}/{sequence}/preview'] as $invalid) {
            $altered = $paths;
            $altered[1] = $invalid;
            try {
                new RequiredPaths(...$altered);
                self::fail('A foreign path or incorrect native parameter was admitted.');
            } catch (\InvalidArgumentException $exception) {
                self::assertNotEmpty($exception->getMessage());
            }
        }
        $this->expectException(\InvalidArgumentException::class);
        $native->target('pause', '../foreign');
    }
}
