<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

/** Explicit paths supplied only after the native V1 routes are mounted. No URI defaults. */
final readonly class RequiredPaths
{
    public function __construct(public string $list, public string $preview, public string $create,
        public string $save, public string $enrol, public string $originalSave, public string $originalEnrol,
        public string $pause, public string $resume, public string $cancel)
    {
        foreach (get_object_vars($this) as $name => $path) {
            $placeholders = in_array($name, ['preview', 'save', 'enrol'], true) ? ['sequence']
                : (in_array($name, ['pause', 'resume', 'cancel'], true) ? ['enrolment'] : []);
            if (! is_string($path) || $path === '' || $path[0] === '/' || preg_match('/^[a-zA-Z0-9_{}\/.\-]+$/D', $path) !== 1
                || str_contains($path, '//') || in_array('..', explode('/', $path), true)) {
                throw new \InvalidArgumentException('Supply a mounted V1 path relative to the configured SDK base URL.');
            }
            preg_match_all('/\{([^}]+)\}/', $path, $matches);
            if ($matches[1] !== $placeholders || str_contains(preg_replace('/\{[^}]+\}/', '', $path) ?? '', '{')
                || str_contains(preg_replace('/\{[^}]+\}/', '', $path) ?? '', '}')) {
                throw new \InvalidArgumentException('The path must contain exactly its native route parameter.');
            }
        }
    }

    public function target(string $operation, string $id): string
    {
        WireCodec::id($id);
        if (! in_array($operation, ['preview', 'save', 'enrol', 'pause', 'resume', 'cancel'], true)) {
            throw new \InvalidArgumentException('Unknown sequence route operation.');
        }

        return str_replace(['{sequence}', '{enrolment}'], rawurlencode($id), $this->{$operation});
    }
}
