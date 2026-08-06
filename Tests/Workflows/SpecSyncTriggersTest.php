<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Workflows;

use PHPUnit\Framework\TestCase;

final class SpecSyncTriggersTest extends TestCase
{
    private const REQUIRED_TRIGGERS = ['repository_dispatch', 'workflow_dispatch', 'schedule'];

    // workflow_dispatch is excluded on purpose: it only fires when a human
    // remembers, so it is not coverage.
    private const UNATTENDED_TRIGGERS = ['repository_dispatch', 'schedule'];

    private const PAUSE_NOTE = '/^#\s*PAUSED\s+(\S+)\s+since\s+\d{4}-\d{2}-\d{2};\s*reactivate when:\s*\S/';

    private const PAUSE_TEMPLATE = '# PAUSED <trigger> since <YYYY-MM-DD>; reactivate when: <condition anyone reading this file can check>';

    private const FIXTURE = <<<'YAML'
        on:
          repository_dispatch:
            types: [spec-updated]
          workflow_dispatch:
          # PAUSED schedule since 2026-06-07; reactivate when: the published spec
          # has at least as many paths as spec/openapi.json
          # schedule:
          #   - cron: "17 6 * * *"

        permissions:
          contents: write
          schedule: this-is-not-a-trigger
        YAML;

    public function test_does_not_count_a_commented_out_trigger_as_active(): void
    {
        $this->assertSame(
            ['repository_dispatch', 'workflow_dispatch'],
            self::activeTriggers(self::FIXTURE),
        );
    }

    public function test_reads_the_pause_note_of_a_disabled_trigger(): void
    {
        $this->assertSame(['schedule'], self::pausedTriggers(self::FIXTURE));
    }

    public function test_rejects_a_pause_note_without_a_date_or_without_a_condition(): void
    {
        $vague = implode("\n", [
            '# PAUSED schedule since 2026-06-07; reactivate when:',
            '# PAUSED schedule; reactivate when: docs == prod',
            '# schedule paused for now, will re-enable later',
        ]);

        $this->assertSame([], self::pausedTriggers($vague));
    }

    public function test_keeps_at_least_one_unattended_trigger_active(): void
    {
        $active = self::activeTriggers(self::workflow());
        $covering = array_intersect(self::UNATTENDED_TRIGGERS, $active);

        $this->assertNotEmpty($covering, sprintf(
            'spec-sync.yml has no unattended trigger left (%s are all off). workflow_dispatch '.
            'alone is not coverage: it fires only when someone remembers. That state is what left '.
            'this SDK two months behind the published spec without a signal, so it has no opt-out '.
            '— restore a trigger, or remove this test in a reviewed PR that says why the SDK is '.
            'going uncovered.',
            implode(' / ', self::UNATTENDED_TRIGGERS),
        ));
    }

    public function test_every_disabled_trigger_declares_a_checkable_reactivation_condition(): void
    {
        $source = self::workflow();
        $undeclared = array_values(array_diff(
            self::REQUIRED_TRIGGERS,
            self::activeTriggers($source),
            self::pausedTriggers($source),
        ));

        $this->assertSame([], $undeclared, sprintf(
            "Disabled with no declared pause: %s. Add this line next to the disabled block:\n\n".
            "  %s\n\nThe condition has to be checkable by whoever reads the file, without knowing ".
            'the context of whoever paused it.',
            implode(', ', $undeclared),
            self::PAUSE_TEMPLATE,
        ));
    }

    public function test_a_restored_trigger_drops_its_pause_note(): void
    {
        $source = self::workflow();
        $stale = array_values(array_intersect(
            self::pausedTriggers($source),
            self::activeTriggers($source),
        ));

        $this->assertSame([], $stale, sprintf(
            'Active trigger still carrying a pause note: %s. Reactivating replaces the note with '.
            'the evidence that its condition was met, and the date it was met.',
            implode(', ', $stale),
        ));
    }

    private static function workflow(): string
    {
        return file_get_contents(__DIR__.'/../../.github/workflows/spec-sync.yml');
    }

    /**
     * @return list<string>
     */
    private static function activeTriggers(string $source): array
    {
        $found = [];
        $inOnBlock = false;

        foreach (explode("\n", $source) as $line) {
            if (preg_match('/^on:/', $line) === 1) {
                $inOnBlock = true;

                continue;
            }
            if (! $inOnBlock) {
                continue;
            }
            if (preg_match('/^[^\s#]/', $line) === 1) {
                break;
            }
            if (preg_match('/^ {2}([a-z_]+):/', preg_replace('/#.*$/', '', $line), $key) === 1) {
                $found[] = $key[1];
            }
        }

        return array_values(array_unique($found));
    }

    /**
     * @return list<string>
     */
    private static function pausedTriggers(string $source): array
    {
        $paused = [];

        foreach (explode("\n", $source) as $line) {
            if (preg_match(self::PAUSE_NOTE, trim($line), $note) === 1) {
                $paused[] = $note[1];
            }
        }

        return array_values(array_unique($paused));
    }
}
