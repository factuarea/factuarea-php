<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceFormReferences;

/** The current protected native V1 path must be supplied explicitly; construction activates no capability. */
final readonly class RequiredPaths
{
    public function __construct(public string $getServiceFormReferences)
    {
        $path = $getServiceFormReferences;
        if ($path === '' || $path[0] === '/' || preg_match('/^[a-zA-Z0-9_\/\.\-]+$/D', $path) !== 1
            || str_contains($path, '//') || in_array('..', explode('/', $path), true) || in_array('.', explode('/', $path), true)) {
            throw new \InvalidArgumentException('Supply the actual native path relative to the configured SDK server, without parameters or target placeholders.');
        }
    }
}
