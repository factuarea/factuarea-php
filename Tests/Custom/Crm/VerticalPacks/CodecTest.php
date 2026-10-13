<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\VerticalPacks\ApplyRequest;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Conflicts;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Installation;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\OriginalReceipt;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Pack;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\PackPage;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Plan;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\PlanItem;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Receipt;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\ReceiptItem;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Resolution;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\TranslatedText;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\UndoRequest;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\WireCodec;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** Offline producer ABI fixtures only, never Company approval, encrypted-token verification or server execution. */
final class CodecTest extends TestCase
{
    public const PLAN = '019a4afd-7100-7000-8000-000000000001';
    public const PACK = '019a4afd-7100-7000-8000-000000000002';
    public const ITEM = '019a4afd-7100-7000-8000-000000000003';
    public const INSTALLATION = '019a4afd-7100-7000-8000-000000000004';
    public const RECEIPT = '019a4afd-7100-7000-8000-000000000005';
    public const TARGET = '019a4afd-7100-7000-8000-000000000006';

    public static function text(): TranslatedText
    {
        return new TranslatedText('Configuración ñ', 'Native configuration', 'Configuració nativa');
    }
    public static function plan(bool $conflict = false): Plan
    {
        $text = self::text();

        return new Plan(self::PLAN, 1, self::PACK, 3, hash('sha256', 'original full native universe'), 'opaque-original-encrypted-selector',
            'native-current:C7', false, false, [new PlanItem(self::ITEM, 'field', 'lead.sector', $text,
                $conflict ? 'conflict' : 'add', $conflict ? 9 : null, $conflict ? ['keep'] : [], $conflict ? $text : null, $text)], []);
    }
    public static function applyWire(?Plan $plan = null): string
    {
        $plan ??= self::plan();

        return ' '.(new ApplyRequest(1, $plan->previewHash, $plan->planToken, [], false, true))->toJson()." \n";
    }
    public static function receipt(): Receipt
    {
        return new Receipt(self::RECEIPT, 'apply', self::PLAN, true,
            new Installation(self::INSTALLATION, 12, self::RECEIPT, self::PACK, 3), [new ReceiptItem(self::ITEM, 'added', self::TARGET, 1)]);
    }

    public function test_native_catalogue_is_closed_translated_and_has_no_whole_profile(): void
    {
        $page = new PackPage([new Pack(self::PACK, 3, 'professional_services', self::text(), self::text(), false, null)], null);
        $decoded = PackPage::fromArray($page->toArray());
        self::assertSame(self::PACK, $decoded->items[0]->id);
        self::assertSame('Configuració nativa', $decoded->items[0]->title->ca);
        self::assertNull($decoded->nextCursor);
        $values = $decoded->items[0]->toArray();
        $values['profile'] = (object) ['approved' => true];
        self::reject(static fn () => Pack::fromArray($values));
    }

    public function test_plan_retains_exact_opaque_token_hash_current_c7_and_null_current_values(): void
    {
        $original = self::plan();
        $decoded = Plan::fromArray($original->toArray());
        self::assertSame($original->planToken, $decoded->planToken);
        self::assertSame($original->previewHash, $decoded->previewHash);
        self::assertSame('native-current:C7', $decoded->authorizationVersion);
        self::assertNull($decoded->items[0]->currentVersion);
        self::assertNull($decoded->items[0]->currentDisplay);
        self::assertArrayNotHasKey('expires_at', $decoded->toArray());
        self::assertArrayNotHasKey('source_revision', $decoded->toArray());
        $bad = $original->toArray();
        $items = $bad['items'];
        self::assertIsArray($items);
        self::assertInstanceOf(\stdClass::class, $items[0]);
        $items[0]->current_version = 9;
        self::reject(static fn () => Plan::fromArray($bad));
    }

    public function test_exact_raw_wire_and_lowercase_original_key_survive_validation_and_recovery_tuple(): void
    {
        $plan = self::plan();
        $wire = self::applyWire($plan);
        $key = hash('sha256', 'client-retained-original-key');
        $intent = OriginalIntent::apply($plan, $wire, $key);
        self::assertSame($wire, $intent->originalWire);
        self::assertSame($key, $intent->originalKey);
        self::assertSame(self::PLAN, $intent->targetId);
        $recovered = OriginalIntent::retained($intent->operation, $intent->targetId, $intent->originalWire, $intent->originalKey);
        self::assertSame($intent->originalWire, $recovered->originalWire);
        self::assertSame('[redacted]', $intent->__debugInfo()['originalWire']);
    }

    /** @return iterable<string,array{string}> */
    public static function badKeys(): iterable
    {
        yield 'short' => ['key'];
        yield 'uppercase' => [str_repeat('A', 64)];
        yield 'header_space' => [' '.str_repeat('a', 64)];
        yield 'newline' => [str_repeat('a', 63)."\n"];
    }
    #[DataProvider('badKeys')]
    public function test_original_key_is_not_generated_trimmed_or_coerced(string $key): void
    {
        self::reject(static fn () => OriginalIntent::apply(self::plan(), self::applyWire(), $key));
    }

    /** @return iterable<string,array{string,mixed}> */
    public static function replacedOriginalPlan(): iterable
    {
        yield 'token' => ['plan_token', 'different-selector'];
        yield 'hash' => ['preview_hash', str_repeat('a', 64)];
        yield 'version' => ['expected_plan_version', 2];
        yield 'demo' => ['demo_selected', true];
        yield 'confirmation' => ['confirmed', false];
    }
    #[DataProvider('replacedOriginalPlan')]
    public function test_apply_rejects_changed_original_plan_tuple_without_generating_a_replacement(string $field, mixed $value): void
    {
        $body = json_decode(self::applyWire(), true, 32, JSON_THROW_ON_ERROR);
        self::assertIsArray($body);
        $body[$field] = $value;
        self::reject(static fn () => OriginalIntent::apply(self::plan(), json_encode($body, JSON_THROW_ON_ERROR), str_repeat('b', 64)));
    }

    public function test_conflict_resolution_is_exactly_one_allowed_choice_and_never_implicitly_replace(): void
    {
        $plan = self::plan(true);
        $wire = (new ApplyRequest(1, $plan->previewHash, $plan->planToken, [new Resolution(self::ITEM, 'keep')], false, true))->toJson();
        self::assertSame($wire, OriginalIntent::apply($plan, $wire, str_repeat('c', 64))->originalWire);
        self::reject(static fn () => OriginalIntent::apply($plan, self::applyWire($plan), str_repeat('c', 64)));
        self::reject(static fn () => OriginalIntent::apply($plan,
            (new ApplyRequest(1, $plan->previewHash, $plan->planToken, [new Resolution(self::ITEM, 'replace')], false, true))->toJson(), str_repeat('c', 64)));
        self::reject(static fn () => new ApplyRequest(1, $plan->previewHash, $plan->planToken,
            [new Resolution(self::ITEM, 'keep'), new Resolution(self::ITEM, 'keep')], false, true));
    }

    public function test_demo_requires_explicit_sandbox_and_token_is_bounded_by_bytes_without_client_ttl_extension(): void
    {
        $plan = self::plan()->toArray();
        $plan['demo_selected'] = true;
        self::reject(static fn () => Plan::fromArray($plan));
        $plan['sandbox'] = true;
        self::assertTrue(Plan::fromArray($plan)->demoSelected);
        self::reject(static fn () => WireCodec::token(str_repeat('ñ', 16385)));
        $plan['expires_at'] = '2099-01-01T00:00:00Z';
        self::reject(static fn () => Plan::fromArray($plan));
    }

    public function test_original_receipt_null_is_absence_and_never_a_new_claim_or_current_head(): void
    {
        self::assertNull(OriginalReceipt::fromArray(['data' => null])->receipt);
        $receipt = self::receipt();
        $decoded = OriginalReceipt::fromArray(['data' => $receipt->jsonSerialize()]);
        self::assertInstanceOf(Receipt::class, $decoded->receipt);
        self::assertSame(self::RECEIPT, $decoded->receipt->id);
        self::assertSame(self::INSTALLATION, $decoded->receipt->installation->id);
        self::reject(static fn () => OriginalReceipt::fromArray(['data' => null, 'latest' => $receipt->jsonSerialize()]));
    }

    public function test_undo_binds_native_original_receipt_and_installation_version(): void
    {
        $original = self::receipt();
        $wire = ' '.(new UndoRequest(12, self::RECEIPT, true))->toJson()." \n";
        $intent = OriginalIntent::undo($original, $wire, str_repeat('d', 64));
        self::assertSame(self::INSTALLATION, $intent->targetId);
        self::assertSame($wire, $intent->originalWire);
        self::reject(static fn () => OriginalIntent::undo($original, (new UndoRequest(13, self::RECEIPT, true))->toJson(), str_repeat('d', 64)));
        self::reject(static fn () => OriginalIntent::undo($original, (new UndoRequest(12, self::TARGET, true))->toJson(), str_repeat('d', 64)));
    }

    public function test_receipt_keeps_paired_nullable_target_version_and_original_installation_link(): void
    {
        $item = ReceiptItem::fromArray(['item_id' => self::ITEM, 'outcome' => 'undone', 'target_id' => null, 'version' => null]);
        self::assertNull($item->targetId);
        self::assertNull($item->version);
        self::reject(static fn () => new ReceiptItem(self::ITEM, 'kept', null, 7));
        self::reject(static fn () => new Receipt(self::RECEIPT, 'apply', self::PLAN, true,
            new Installation(self::INSTALLATION, 12, self::TARGET, self::PACK, 3), []));
    }

    public function test_original_wire_size_and_duplicate_members_cannot_change_effect_fingerprint(): void
    {
        $wire = self::applyWire();
        $boundary = $wire.str_repeat(' ', 65536 - strlen($wire));
        self::assertSame(65536, strlen(OriginalIntent::retained('apply', self::PLAN, $boundary, str_repeat('e', 64))->originalWire));
        self::reject(static fn () => OriginalIntent::retained('apply', self::PLAN, $boundary.' ', str_repeat('e', 64)));
        self::reject(static fn () => WireCodec::apply(str_replace('"confirmed":true', '"confirmed":false,"confirmed":true', $wire)));
    }

    public function test_conflict_query_cannot_substitute_nonconflicts_or_leak_private_current_specs(): void
    {
        $plan = self::plan(true);
        $result = new Conflicts($plan->items, $plan->previewHash);
        self::assertSame($plan->previewHash, Conflicts::fromArray($result->toArray())->previewHash);
        self::reject(static fn () => new Conflicts(self::plan()->items, $plan->previewHash));
        $private = $plan->items[0]->toArray();
        $private['current_spec'] = (object) ['secret' => true];
        self::reject(static fn () => PlanItem::fromArray($private));
    }

    public function test_native_catalogue7_keeps_explicit_false_and_current_installation_as_required_facts(): void
    {
        $pack = new Pack(self::PACK, 99, 'professional_services', self::text(), self::text(), false,
            new Installation(self::INSTALLATION, 88, self::TARGET, self::PACK, 4));
        $decoded = Pack::fromArray($pack->toArray());
        self::assertFalse($decoded->supportsDemo);
        self::assertInstanceOf(Installation::class, $decoded->installation);
        self::assertSame(88, $decoded->installation->version);
        self::assertSame(self::TARGET, $decoded->installation->receiptId);
        self::assertSame(4, $decoded->installation->packVersion);
        self::assertSame(99, $decoded->version);
        $absent = $pack->toArray();
        $absent['installation'] = null;
        self::assertNull(Pack::fromArray($absent)->installation);
        foreach (['supports_demo', 'installation'] as $field) {
            $missing = $pack->toArray();
            unset($missing[$field]);
            self::reject(static fn () => Pack::fromArray($missing));
        }
        $zero = $pack->toArray();
        $zero['supports_demo'] = 0;
        self::reject(static fn () => Pack::fromArray($zero));
        self::reject(static fn () => new Pack(self::PACK, 99, 'professional_services', self::text(), self::text(), false,
            new Installation(self::INSTALLATION, 88, self::RECEIPT, self::TARGET, 4)));
    }

    private static function reject(callable $operation): void
    {
        try {
            $operation();
            self::fail('The closed native protocol must reject this value.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
    }
}
