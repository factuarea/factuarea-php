<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Factuarea\Sdk\Custom\Crm\Schema;

final class CrmGeneratorCompatibilityTest extends TestCase
{
    public function test_generator_keeps_request_enums_closed_and_opens_response_enums(): void
    {
        $repository = dirname(__DIR__, 3);
        $temporary = sys_get_temp_dir().'/factuarea-crm-generator-'.bin2hex(random_bytes(8));

        foreach (['tools', 'spec', 'src/Custom/Crm/Model', 'src/Custom/Crm'] as $directory) {
            if (! is_dir($temporary.'/'.$directory)) {
                mkdir($temporary.'/'.$directory, 0777, true);
            }
        }
        copy($repository.'/tools/generate-crm.py', $temporary.'/tools/generate-crm.py');
        copy($repository.'/spec/crm-openapi.json', $temporary.'/spec/crm-openapi.json');
        copy($repository.'/spec/crm-effects.json', $temporary.'/spec/crm-effects.json');

        try {
            [$exit, $stdout, $stderr] = $this->runCommand([
                'python3',
                $temporary.'/tools/generate-crm.py',
                '--model', 'LeadHistoryItemVariant3Snapshot',
                '--model', 'CrmGetLeadHistoryResult',
                '--model', 'CrmCreateLeadRequest',
                '--model', 'CrmCreateLeadRequestEstimatedValue',
                '--model', 'CrmFindLeadParameters',
                '--model', 'CrmRecordLeadConsentRequest',
            ]);
            self::assertSame(0, $exit, $stdout."\n".$stderr);

            $responseModel = file_get_contents($temporary.'/src/Custom/Crm/Model/LeadHistoryItemVariant3Snapshot.php');
            $responseResult = file_get_contents($temporary.'/src/Custom/Crm/Model/CrmGetLeadHistoryResult.php');
            $requestModel = file_get_contents($temporary.'/src/Custom/Crm/Model/CrmCreateLeadRequest.php');
            $decimalModel = file_get_contents($temporary.'/src/Custom/Crm/Model/CrmCreateLeadRequestEstimatedValue.php');
            $findLeadParameters = file_get_contents($temporary.'/src/Custom/Crm/Model/CrmFindLeadParameters.php');
            $consentRequest = file_get_contents($temporary.'/src/Custom/Crm/Model/CrmRecordLeadConsentRequest.php');
            self::assertIsString($responseModel);
            self::assertIsString($responseResult);
            self::assertIsString($requestModel);
            self::assertIsString($decimalModel);
            self::assertIsString($findLeadParameters);
            self::assertIsString($consentRequest);
            self::assertStringNotContainsString("'enum' =>", $responseModel);
            self::assertStringNotContainsString("'enum' =>", $responseResult);
            self::assertStringContainsString("'enum' => ['manual']", $requestModel);
            self::assertStringContainsString('public string $amount', $decimalModel);
            self::assertStringContainsString('public string $currency', $decimalModel);

            $runner = '<?php'."\n"
                .'require '.var_export($repository.'/vendor/autoload.php', true).";\n"
                .'require '.var_export($temporary.'/src/Custom/Crm/Model/LeadHistoryItemVariant3Snapshot.php', true).";\n"
                .'require '.var_export($temporary.'/src/Custom/Crm/Model/CrmCreateLeadRequest.php', true).";\n"
                .'require '.var_export($temporary.'/src/Custom/Crm/Model/CrmCreateLeadRequestEstimatedValue.php', true).";\n"
                .'require '.var_export($temporary.'/src/Custom/Crm/Model/CrmFindLeadParameters.php', true).";\n"
                .'require '.var_export($temporary.'/src/Custom/Crm/Model/CrmRecordLeadConsentRequest.php', true).";\n"
                .'$snapshot = \\Factuarea\\Sdk\\Custom\\Crm\\Model\\LeadHistoryItemVariant3Snapshot::fromArray([\'type\' => \'lead.future_event\']);'."\n"
                .'if ($snapshot->type !== \'lead.future_event\') { exit(10); }'."\n"
                .'$request = new \\Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmCreateLeadRequest(name: \'Lead\', source: \'manual\');'."\n"
                .'if (array_key_exists(\'email\', $request->toArray())) { exit(11); }'."\n"
                .'$request->email = null;'."\n"
                .'if (! array_key_exists(\'email\', $request->toArray()) || $request->toArray()[\'email\'] !== null) { exit(12); }'."\n"
                .'try { new \\Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmCreateLeadRequest(name: \'Lead\', source: \'future\'); exit(13); } catch (\\InvalidArgumentException) {}'."\n"
                .'$amount = \'12345678901234567890.12345678901234567890\';'."\n"
                .'$value = new \\Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmCreateLeadRequestEstimatedValue($amount, \'EUR\');'."\n"
                .'if ($value->toArray()[\'amount\'] !== $amount) { exit(14); }'."\n"
                .'$id = \'019a4afd-5000-7000-8000-000000000001\';'."\n"
                .'new \\Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmFindLeadParameters(lead: $id, factuareaVersion: \'2024-02-29\');'."\n"
                .'try { new \\Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmFindLeadParameters(lead: $id, factuareaVersion: \'2026-02-30\'); exit(15); } catch (\\InvalidArgumentException) {}'."\n"
                .'try { new \\Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmFindLeadParameters(lead: $id, xActiveProfile: \'not-a-uuid\'); exit(16); } catch (\\InvalidArgumentException) {}'."\n"
                .'$consent = [\'expected_version\' => 1, \'purpose_key\' => \'lead_contact\', \'state\' => \'granted\', \'notice_version\' => 1, \'notice_hash\' => str_repeat(\'a\', 64), \'locale\' => \'es\', \'evidence_medium\' => \'manual\', \'evidence_at\' => \'2026-02-30T12:00:00Z\', \'author_type\' => \'manual\'];'."\n"
                .'try { \\Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmRecordLeadConsentRequest::fromArray($consent); exit(17); } catch (\\InvalidArgumentException) {}'."\n";
            $runnerPath = $temporary.'/check-generated-models.php';
            self::assertNotFalse(file_put_contents($runnerPath, $runner));
            [$runtimeExit, $runtimeOut, $runtimeErr] = $this->runCommand([PHP_BINARY, $runnerPath]);
            self::assertSame(0, $runtimeExit, $runtimeOut."\n".$runtimeErr);
        } finally {
            $this->removeDirectory($temporary);
        }
    }

    public function test_schema_validates_openapi_formats_and_preserves_wire_strings(): void
    {
        self::assertNull(Schema::validate(['type' => 'string', 'format' => 'uuid'], '019a4afd-5000-7000-8000-000000000001'));
        self::assertNull(Schema::validate(['type' => 'string', 'format' => 'date'], '2024-02-29'));
        self::assertNull(Schema::validate(['type' => 'string', 'format' => 'date-time'], '2026-06-15T12:30:00+02:00'));
        self::assertNull(Schema::validate(['type' => 'string', 'format' => 'uri'], 'https://example.test/crm'));
        self::assertNull(Schema::validate(['type' => 'string', 'format' => 'email'], 'crm@example.test'));
        self::assertNull(Schema::validate(['type' => 'string', 'pattern' => '^[A-Z]{3}$'], 'EUR'));
        self::assertNull(Schema::validate(['type' => 'string', 'pattern' => '^(0|[1-9][0-9]*)(\\.[0-9]+)?$'], '9007199254740993.0100'));

        foreach ([
            [['type' => 'string', 'format' => 'uuid'], 'not-a-uuid'],
            [['type' => 'string', 'format' => 'date'], '2026-02-30'],
            [['type' => 'string', 'format' => 'date-time'], '2026-02-30T12:30:00Z'],
            [['type' => 'string', 'format' => 'uri'], 'not a uri'],
            [['type' => 'string', 'format' => 'email'], 'not-an-email'],
            [['type' => 'string', 'pattern' => '^[A-Z]{3}$'], 'eur'],
        ] as [$schema, $value]) {
            try {
                Schema::validate($schema, $value);
                self::fail('The native OpenAPI value must be rejected: '.(string) $value);
            } catch (\InvalidArgumentException) {
                // Format and currency validation must preserve the caller's wire representation.
            }
        }
    }

    /** @return array{int, string, string} */
    private function runCommand(array $command): array
    {
        $pipes = [];
        $process = proc_open($command, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        self::assertIsResource($process);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), $stdout === false ? '' : $stdout, $stderr === false ? '' : $stderr];
    }

    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }
        foreach (scandir($directory) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $directory.'/'.$entry;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($directory);
    }
}
