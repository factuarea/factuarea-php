<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

/** Explicit mounted V1 paths, relative to the SAME configured native SDK server. */
final readonly class RequiredPaths
{
    public function __construct(public string $link, public string $unlink, public string $tasks,
        public string $billable, public string $timesheet, public string $preview,
        public string $originalLink, public string $originalUnlink)
    {
        foreach (get_object_vars($this) as $path) {
            if ($path === '' || $path[0] === '/' || preg_match('/^[a-zA-Z0-9_{}\/.\-]+$/D', $path) !== 1
                || str_contains($path, '//') || in_array('..', explode('/', $path), true)) {
                throw new \InvalidArgumentException('Supply a mounted V1 path relative to the configured SDK server.');
            }
            preg_match_all('/\{([^}]+)\}/', $path, $matches);
            $rest = preg_replace('/\{[^}]+\}/', '', $path) ?? '';
            if ($matches[1] !== ['ticket'] || str_contains($rest, '{') || str_contains($rest, '}')) {
                throw new \InvalidArgumentException('Each native Task time path requires exactly {ticket}.');
            }
        }
    }

    public function target(string $operation, string $ticketId): string
    {
        WireCodec::id($ticketId);
        if (! in_array($operation, ['link', 'unlink', 'tasks', 'billable', 'timesheet', 'preview', 'originalLink', 'originalUnlink'], true)) {
            throw new \InvalidArgumentException('Unknown Task time route.');
        }

        return str_replace('{ticket}', rawurlencode($ticketId), $this->{$operation});
    }
}
