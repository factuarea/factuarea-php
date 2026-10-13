<?php

declare(strict_types=1);

/** Run only the new installed-consumer tests. The caller creates a real isolated Composer consumer first. */
if (PHP_SAPI !== 'cli' || !in_array(count($argv), [4, 5], true)) {
    fwrite(STDERR, "Usage: php scripts/verify-crm-installed-batch82.php <private-consumer> <private-proof-directory> <frozen-source-handoff.json> [affected-test-filter]\n");
    exit(2);
}

function installedBatch82Fail(string $code): never
{
    fwrite(STDERR, json_encode(['result' => 'FAIL', 'code' => $code], JSON_THROW_ON_ERROR)."\n");
    exit(1);
}

$project = realpath($argv[1]);
$proof = realpath($argv[2]);
$handoffPath = realpath($argv[3]);
$sdkSource = realpath(dirname(__DIR__));
$filter = $argv[4] ?? null;
if ($filter !== null && preg_match('/^[A-Za-z0-9_|]{1,255}$/D', $filter) !== 1) {
    installedBatch82Fail('invalid_affected_test_filter');
}
if ($project === false || $proof === false || $handoffPath === false || $sdkSource === false
    || !is_dir($proof) || !is_file($handoffPath) || !is_file($project.'/vendor/bin/phpunit')
    || str_starts_with($project, $sdkSource.'/') || str_starts_with($proof, $sdkSource.'/')) {
    installedBatch82Fail('private_consumer_or_proof_unavailable');
}
try {
    $handoff = json_decode(file_get_contents($handoffPath), true, 512, JSON_THROW_ON_ERROR);
    if (($handoff['repo']['root'] ?? null) !== $sdkSource || count($handoff['repo']['source_pins'] ?? []) !== 25) {
        installedBatch82Fail('frozen_source_handoff_mismatch');
    }
    foreach ($handoff['repo']['source_pins'] as $pin) {
        if (!is_file($pin['absolute_path']) || !hash_equals($pin['sha256'], hash_file('sha256', $pin['absolute_path']))) {
            installedBatch82Fail('frozen_source_changed');
        }
    }
} catch (Throwable) {
    installedBatch82Fail('invalid_frozen_handoff');
}

$environment = getenv();
$environment['FACTUAREA_INSTALLED_BATCH82_ROOT'] = $project;
$environment['FACTUAREA_INSTALLED_BATCH82_HANDOFF'] = $handoffPath;
$environment['FACTUAREA_INSTALLED_BATCH82_PROOF'] = $proof;
// The tests set their own synthetic server environment credential; no ambient real credential is consumed.
unset($environment['FACTUAREA_API_KEY']);
$junitPath = $proof.'/installed-phpunit.xml';
$command = [PHP_BINARY, $project.'/vendor/bin/phpunit', '--no-configuration', '--bootstrap',
    $sdkSource.'/Tests/Custom/Crm/InstalledBatch82/Bootstrap.php', '--testdox', '--display-warnings',
    '--do-not-cache-result', '--log-junit', $junitPath, $sdkSource.'/Tests/Custom/Crm/InstalledBatch82/InstalledConsumerTest.php'];
if ($filter !== null) {
    $command[] = '--filter';
    $command[] = $filter;
}
$pipes = [];
$process = proc_open($command, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $project, $environment);
if (!is_resource($process)) {
    installedBatch82Fail('private_test_process_unavailable');
}
fclose($pipes[0]);
$stdout = stream_get_contents($pipes[1]);
$stderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$exitCode = proc_close($process);
foreach (['installed-phpunit.stdout.log' => $stdout, 'installed-phpunit.stderr.log' => $stderr] as $name => $contents) {
    file_put_contents($proof.'/'.$name, $contents);
    chmod($proof.'/'.$name, 0600);
}
if (!is_file($junitPath)) {
    installedBatch82Fail('installed_test_report_missing');
}
chmod($junitPath, 0600);
$report = simplexml_load_file($junitPath);
if ($report === false || !isset($report->testsuite)) {
    installedBatch82Fail('installed_test_report_invalid');
}
$suite = $report->testsuite;
$loader = require $project.'/vendor/autoload.php';
$package = \Composer\InstalledVersions::getInstallPath('factuarea/factuarea-php');
if (!is_string($package) || ($package = realpath($package)) === false) {
    installedBatch82Fail('installed_package_missing');
}
$classes = [];
foreach ([\Factuarea\Sdk\Factuarea::class, \Factuarea\Sdk\Custom\FactuareaClient::class,
    \Factuarea\Sdk\Custom\Crm\SalesBatch81\Client::class, \Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalReceipt::class,
    \Factuarea\Sdk\Custom\Crm\Documents\Omitted::class, \Factuarea\Sdk\Custom\CrmMetaNativeBatch80\Client::class] as $class) {
    $file = $loader->findFile($class);
    $classes[$class] = is_string($file) ? ['file' => $file, 'sha256' => hash_file('sha256', $file)] : null;
}
$preserved = true;
foreach ($handoff['repo']['source_pins'] as $pin) {
    $preserved = $preserved && is_file($pin['absolute_path']) && hash_equals($pin['sha256'], hash_file('sha256', $pin['absolute_path']));
}
$result = ['kind' => 'php-sdk-installed-batch82-executed-proof', 'result' => $exitCode === 0 ? 'PASS' : 'FAIL',
    'php_version' => PHP_VERSION, 'php_binary' => PHP_BINARY, 'php82_executed' => PHP_MAJOR_VERSION === 8 && PHP_MINOR_VERSION === 2,
    'tests' => (int) $suite['tests'], 'assertions' => (int) $suite['assertions'], 'failures' => (int) $suite['failures'],
    'errors' => (int) $suite['errors'], 'exit_code' => $exitCode, 'consumer_directory' => $project,
    'installed_package_directory' => $package, 'installed_package_version' => \Composer\InstalledVersions::getPrettyVersion('factuarea/factuarea-php'),
    'package_is_symlink' => is_link($project.'/vendor/factuarea/factuarea-php'), 'classmap_authoritative' => $loader->isClassMapAuthoritative(),
    'actual_installed_class_files' => $classes, 'frozen_source_pins_preserved' => $preserved,
    'frozen_handoff_sha256' => hash_file('sha256', $handoffPath), 'consumer_manifest_sha256' => hash_file('sha256', $project.'/composer.json'),
    'consumer_lock_sha256' => hash_file('sha256', $project.'/composer.lock'), 'junit_sha256' => hash_file('sha256', $junitPath),
    'scope' => 'Only NEW installed-consumer fake HTTP tests; real Composer autoload/imports/examples; no SDK Source fallback or backend runtime.',
    'affected_test_filter' => $filter,
    'release_or_live_claim' => false];
file_put_contents($proof.'/installed-proof.json', json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n");
chmod($proof.'/installed-proof.json', 0600);
fwrite(STDOUT, json_encode(['result' => $result['result'], 'tests' => $result['tests'], 'assertions' => $result['assertions'],
    'php_version' => $result['php_version'], 'frozen_source_pins_preserved' => $preserved,
    'proof' => $proof.'/installed-proof.json'], JSON_THROW_ON_ERROR)."\n");
exit($exitCode === 0 && $preserved ? 0 : 1);
