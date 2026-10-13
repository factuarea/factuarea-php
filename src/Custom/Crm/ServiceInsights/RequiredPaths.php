<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

/** Three mounted V1 paths supplied by the caller, relative to the canonical SDK base URL. */
final readonly class RequiredPaths
{
    public function __construct(public string $getServiceInsights, public string $getServiceMetrics, public string $getServiceInsightDrillThrough)
    {
        foreach (get_object_vars($this) as $path) {
            if (! is_string($path) || $path === '' || $path[0] === '/' || preg_match('/^[a-zA-Z0-9_\/.\-]+$/D', $path) !== 1
                || str_contains($path, '//') || in_array('..', explode('/', $path), true)) {
                throw new \InvalidArgumentException('Supply explicit mounted V1 read paths without parameters, queries or URI defaults.');
            }
        }
    }
}
