<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

/** Actual HTTP71 paths are supplied explicitly after Root mounting; no URL defaults or release proof. */
final readonly class RequiredPaths
{
    public function __construct(public string $list, public string $preview, public string $plan, public string $conflicts,
        public string $apply, public string $undo, public string $recover)
    {
        $slots = ['list' => [], 'preview' => ['pack'], 'plan' => ['plan'], 'conflicts' => ['plan'], 'apply' => ['plan'], 'undo' => ['installation'], 'recover' => []];
        foreach ($slots as $case => $expected) {
            $path = $this->{$case};
            if ($path === '' || $path[0] === '/' || preg_match('/^[a-zA-Z0-9_{}\/.\-]+$/D', $path) !== 1
                || str_contains($path, '//') || in_array('..', explode('/', $path), true)) {
                throw new \InvalidArgumentException('Supply the actual mounted native V1 path relative to the configured SDK server.');
            }
            preg_match_all('/\{([^}]+)\}/', $path, $matches);
            $rest = preg_replace('/\{[^}]+\}/', '', $path) ?? '';
            if ($matches[1] !== $expected || str_contains($rest, '{') || str_contains($rest, '}')) {
                throw new \InvalidArgumentException('Keep the exact native route target placeholder.');
            }
        }
    }
    public function target(string $case, string $id): string
    {
        WireCodec::id($id);
        $slot = match ($case) {
            'preview' => 'pack', 'plan', 'conflicts', 'apply' => 'plan', 'undo' => 'installation',
            default => throw new \InvalidArgumentException('This native route has no target substitution.')
        };

        return str_replace('{'.$slot.'}', rawurlencode($id), $this->{$case});
    }
}
