<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\EntrySelection;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\PageParameters;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\PreviewRequest;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\RecoveryRequest;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\TimeEntry;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\WireCodec;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CodecTest extends TestCase
{
    private const ID = '019a4afd-5000-7000-8000-000000000001';
    private const TASK = '019a4afd-5000-7000-8000-000000000002';
    private const BINDING = '019a4afd-5000-7000-8000-000000000003';
    private const EFFECT = '019a4afd-5000-7000-8000-000000000004';

    private function wire(string $extra = ''): string
    {
        return '{ "task_id":"'.self::TASK.'","expected_ticket_version":3,"expected_task_version":4,"confirmed":true'.$extra.' }';
    }

    public function test_retains_exact_wire_key_and_uppercase_identifiers(): void
    {
        $wire = str_replace(self::TASK, strtoupper(self::TASK), $this->wire());
        $intent = OriginalIntent::link(strtoupper(self::ID), $wire, 'native opaque key');
        self::assertSame($wire, $intent->originalWire);
        self::assertSame('native opaque key', $intent->originalKey);
        self::assertSame(strtoupper(self::TASK), $intent->taskId);
        self::assertNull($intent->bindingId);
    }

    public function test_exact_native262144_byte_boundary(): void
    {
        $wire = $this->wire();
        $exact = $wire.str_repeat(' ', 262144 - strlen($wire));
        self::assertSame($exact, OriginalIntent::link(self::ID, $exact, 'native-key')->originalWire);
        $this->expectException(\InvalidArgumentException::class);
        OriginalIntent::link(self::ID, $exact.' ', 'native-key');
    }

    public function test_native_key255_bytes_and_interior_spaces_remain_exact(): void
    {
        $key = 'a'.str_repeat(' ', 253).'z';
        self::assertSame($key, OriginalIntent::link(self::ID, $this->wire(), $key)->originalKey);
    }

    /** @return iterable<string,array{string}> */
    public static function badKeys(): iterable
    {
        yield 'empty' => [''];
        yield 'blank' => ['   '];
        yield 'oversize' => [str_repeat('a', 256)];
        yield 'control' => ["native\nkey"];
        yield 'nonASCII' => ['clau-é'];
        yield 'leading OWS cannot survive PSR header' => [' native'];
        yield 'trailing OWS cannot survive PSR header' => ['native '];
    }

    #[DataProvider('badKeys')]
    public function test_rejects_key_before_dispatch(string $key): void
    {
        $this->expectException(\InvalidArgumentException::class);
        OriginalIntent::link(self::ID, $this->wire(), $key);
    }

    /** @return iterable<string,array{string}> */
    public static function invalidExtras(): iterable
    {
        yield 'caller Company' => [',"company_id":4'];
        yield 'caller effect' => [',"effect_id":"'.self::EFFECT.'"'];
        yield 'caller duration' => [',"duration_seconds":50'];
        yield 'caller price' => [',"unit_price":"15.00"'];
        yield 'binding without CAS' => [',"binding_id":"'.self::BINDING.'"'];
        yield 'CAS without binding' => [',"expected_binding_version":2'];
        yield 'false confirmation duplicate' => [',"confirmed":false'];
        yield 'escaped duplicate' => [',"\u0074ask_id":"'.self::TASK.'"'];
    }

    #[DataProvider('invalidExtras')]
    public function test_rejects_authority_members_and_incomplete_binding(string $extra): void
    {
        $this->expectException(\InvalidArgumentException::class);
        OriginalIntent::link(self::ID, $this->wire($extra), 'native-key');
    }

    public function test_omitted_and_explicit_null_binding_are_both_server_allocated(): void
    {
        self::assertNull(OriginalIntent::link(self::ID, $this->wire(), 'first')->bindingId);
        $wire = $this->wire(',"binding_id":null,"expected_binding_version":null');
        $intent = OriginalIntent::link(self::ID, $wire, 'second');
        self::assertNull($intent->bindingId);
        self::assertSame($wire, $intent->originalWire);
    }

    public function test_unlink_requires_known_binding_and_positive_cas(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        OriginalIntent::unlink(self::ID, $this->wire(), 'native-key');
    }

    public function test_recovery_uses_known_i_ds_without_original_wire_or_key(): void
    {
        $request = new RecoveryRequest('unlink', self::TASK, 3, 4, self::BINDING, self::EFFECT, 2);
        $data = json_decode($request->toJson(), true, 32, JSON_THROW_ON_ERROR);
        self::assertSame(self::EFFECT, $data['effect_id']);
        self::assertSame(self::BINDING, $data['binding_id']);
        self::assertArrayNotHasKey('original_wire_body', $data);
        self::assertArrayNotHasKey('original_key', $data);
    }

    public function test_recovery_cannot_use_another_action_or_missing_original_i_ds(): void
    {
        $wire = (new RecoveryRequest('link', self::TASK, 3, 4, self::BINDING, self::EFFECT))->toJson();
        $this->expectException(\InvalidArgumentException::class);
        WireCodec::recovery('unlink', $wire);
    }

    public function test_preview_cannot_select_an_entry_twice(): void
    {
        $entry = new EntrySelection(self::EFFECT, str_repeat('a', 64));
        $this->expectException(\InvalidArgumentException::class);
        new PreviewRequest(self::TASK, 3, 4, [$entry, $entry], 'per_entry');
    }

    public function test_duplicate_nested_selection_names_are_rejected(): void
    {
        $wire = '{"task_id":"'.self::TASK.'","expected_ticket_version":3,"expected_task_version":4,"grouping":"per_task","entries":[{"id":"'.self::ID.'","revision":"'.str_repeat('a', 64).'","\u0072evision":"'.str_repeat('b', 64).'"}]}';
        $this->expectException(\InvalidArgumentException::class);
        WireCodec::preview($wire);
    }

    public function test_nullable_masked_time_facts_never_become_defaults(): void
    {
        $entry = TimeEntry::fromArray(['id' => self::ID, 'task_id' => self::TASK, 'ended_at' => null, 'invoice_id' => null]);
        self::assertNull($entry->endedAt);
        self::assertNull($entry->invoiceId);
        self::assertSame(Omitted::Value, $entry->durationSeconds);
        self::assertSame(Omitted::Value, $entry->billable);
        self::assertArrayNotHasKey('duration_seconds', $entry->toArray());
    }

    public function test_rejects_synthetic_paths_and_cross_resource_cursor(): void
    {
        new PageParameters(str_repeat('a', 64).':'.self::ID, 100);
        $this->expectException(\InvalidArgumentException::class);
        new RequiredPaths('https://invented.invalid/{ticket}', 'x/{ticket}', 'x/{ticket}', 'x/{ticket}', 'x/{ticket}', 'x/{ticket}', 'x/{ticket}', 'x/{ticket}');
    }

    public function test_positive_native_cas_cannot_be_string_or_max_integer(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        WireCodec::version(PHP_INT_MAX);
    }
}
