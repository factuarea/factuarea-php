<?php

declare(strict_types=1);

// The test process uses only the real private consumer's Composer autoloader.
$project = getenv('FACTUAREA_INSTALLED_BATCH82_ROOT');
$handoff = getenv('FACTUAREA_INSTALLED_BATCH82_HANDOFF');
$proof = getenv('FACTUAREA_INSTALLED_BATCH82_PROOF');
if (!is_string($project) || ($project = realpath($project)) === false
    || !is_string($handoff) || !is_file($handoff) || !is_string($proof) || ($proof = realpath($proof)) === false || !is_dir($proof)) {
    throw new RuntimeException('The private installed consumer and frozen handoff are required.');
}
if (class_exists(\Factuarea\Sdk\Custom\Crm\SalesBatch81\Client::class, false)) {
    throw new RuntimeException('SDK Source must not be loaded before the installed autoloader.');
}
$loader = require $project.'/vendor/autoload.php';
$package = \Composer\InstalledVersions::getInstallPath('factuarea/factuarea-php');
if (!is_string($package) || is_link($package) || ($package = realpath($package)) === false
    || $package !== $project.'/vendor/factuarea/factuarea-php' || !$loader->isClassMapAuthoritative()) {
    throw new RuntimeException('A mirrored package and authoritative Composer autoload are required.');
}
define('FACTUAREA_INSTALLED_BATCH82_PROJECT', $project);
define('FACTUAREA_INSTALLED_BATCH82_PACKAGE', $package);
define('FACTUAREA_INSTALLED_BATCH82_HANDOFF', $handoff);
define('FACTUAREA_INSTALLED_BATCH82_PROOF', $proof);
$GLOBALS['factuareaInstalledBatch82Loader'] = $loader;

// Examples define functions rather than autoloadable classes; import the installed files only.
require_once $package.'/examples/CrmWebsite.php';
require_once $package.'/examples/CrmJourney.php';
