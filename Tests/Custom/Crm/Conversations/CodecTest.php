<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\Conversations\ConversationListResult;
use Factuarea\Sdk\Custom\Crm\Conversations\ConversationThreadResult;
use Factuarea\Sdk\Custom\Crm\Conversations\Filters;
use Factuarea\Sdk\Custom\Crm\Conversations\GetConversationRequest;
use Factuarea\Sdk\Custom\Crm\Conversations\ListConversationsRequest;
use Factuarea\Sdk\Custom\Crm\Conversations\Message;
use Factuarea\Sdk\Custom\Crm\Omitted;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CodecTest extends TestCase
{
    public function test_public_identity_and_native_delivery_facts_round_trip_without_promoting_intent_or_quarantine(): void
    {
        $wire = ConversationMother::thread();
        $result = ConversationThreadResult::fromArray($wire);
        self::assertSame($wire, json_decode($result->toJson(), true, 512, JSON_THROW_ON_ERROR));
        self::assertSame(ConversationMother::USER, $result->data->conversation->ownerId);
        self::assertSame('send_intent', $result->data->messages[0]->compositionState);
        self::assertSame('unconfirmed', $result->data->messages[0]->deliveryStatus);
        self::assertSame(ConversationMother::AT, $result->data->messages[0]->submittedAt);
        self::assertSame('quarantine', $result->data->attachments[0]->status);
        self::assertSame("Texto sin interpretar.\n{{caller_text}}", $result->data->messages[0]->body);
        self::assertArrayNotHasKey('url', $result->data->attachments[0]->toArray());
        self::assertArrayNotHasKey('conversation_uuid', $result->data->messages[0]->toArray());
    }

    public function test_current_masks_preserve_required_nulls_and_empty_collections_without_fetching_hidden_values(): void
    {
        $wire = ConversationMother::thread();
        $wire['data']['conversation']['subject'] = null;
        $wire['data']['conversation']['owner_id'] = null;
        $wire['data']['conversation']['last_message_at'] = null;
        $wire['data']['conversation']['links'] = [];
        $wire['data']['messages'] = [];
        $wire['data']['attachments'] = [];
        $wire['meta'] = ConversationMother::meta(['uuid', 'version', 'mailbox_uuid', 'status']);
        $result = ConversationThreadResult::fromArray($wire);
        self::assertSame($wire, json_decode($result->toJson(), true, 512, JSON_THROW_ON_ERROR));
        self::assertNull($result->data->conversation->subject);
        self::assertSame([], $result->data->messages);
        self::assertSame([], $result->data->attachments);
    }

    public function test_message_masks_preserve_null_participants_and_body_as_distinct_from_empty_recipient_lists(): void
    {
        $wire = ConversationMother::thread();
        foreach (['sender', 'recipients', 'subject', 'body', 'delivery_status', 'submitted_at'] as $key) {
            $wire['data']['messages'][0][$key] = null;
        }
        $wire['data']['conversation']['subject'] = null;
        $wire['meta']['readable_fields'] = array_values(array_diff($wire['meta']['readable_fields'], ['sender', 'recipients', 'subject', 'body', 'delivery_status']));
        $result = ConversationThreadResult::fromArray($wire);
        self::assertSame($wire, json_decode($result->toJson(), true, 512, JSON_THROW_ON_ERROR));
        self::assertNull($result->data->messages[0]->recipients);
        self::assertNull($result->data->messages[0]->deliveryStatus);
    }

    public function test_future_response_states_remain_strings_without_becoming_confirmed_or_ready(): void
    {
        $wire = ConversationMother::thread();
        $wire['data']['conversation']['status'] = 'future_hold';
        $wire['data']['conversation']['links'][0]['state'] = 'future_link_review';
        $wire['data']['messages'][0]['delivery_status'] = 'future_delivery_review';
        $wire['data']['attachments'][0]['status'] = 'future_attachment_review';
        $result = ConversationThreadResult::fromArray($wire);
        self::assertSame($wire, json_decode($result->toJson(), true, 512, JSON_THROW_ON_ERROR));
        self::assertSame('future_delivery_review', $result->data->messages[0]->deliveryStatus);
        self::assertSame('future_attachment_review', $result->data->attachments[0]->status);
    }

    /** @return iterable<string,array{string}> */
    public static function invalidThreadFacts(): iterable
    {
        foreach (['missing_nullable', 'private_authority', 'hidden_body', 'hidden_attachments', 'foreign_message', 'foreign_attachment',
            'offpage_attachment', 'null_visible_thread', 'duplicate_message', 'duplicate_attachment', 'invalid_date', 'intent_confirmed_without_date',
            'received_outbound', 'empty_visible_recipients', 'historical_business_uuid'] as $case) {
            yield $case => [$case];
        }
    }

    #[DataProvider('invalidThreadFacts')]
    public function test_missing_or_tampered_native_facts_fail_instead_of_inventing_visibility_or_delivery(string $case): void
    {
        $wire = ConversationMother::thread();
        switch ($case) {
            case 'missing_nullable': unset($wire['data']['messages'][0]['sender']);
                break;
            case 'private_authority': $wire['data']['conversation']['company_id'] = 77;
                break;
            case 'hidden_body': $wire['meta']['readable_fields'] = array_values(array_diff($wire['meta']['readable_fields'], ['body']));
                break;
            case 'hidden_attachments': $wire['meta']['readable_fields'] = array_values(array_diff($wire['meta']['readable_fields'], ['attachments']));
                break;
            case 'foreign_message': $wire['data']['messages'][0]['conversation_id'] = ConversationMother::OTHER_CONVERSATION;
                break;
            case 'foreign_attachment': $wire['data']['attachments'][0]['conversation_id'] = ConversationMother::OTHER_CONVERSATION;
                break;
            case 'offpage_attachment': $wire['data']['attachments'][0]['message_id'] = ConversationMother::OTHER_MESSAGE;
                break;
            case 'null_visible_thread': $wire['data']['conversation'] = null;
                break;
            case 'duplicate_message': $wire['data']['messages'][] = $wire['data']['messages'][0];
                break;
            case 'duplicate_attachment': $wire['data']['attachments'][] = $wire['data']['attachments'][0];
                break;
            case 'invalid_date': $wire['data']['messages'][0]['submitted_at'] = '2026-02-30T12:30:01Z';
                break;
            case 'intent_confirmed_without_date': $wire['data']['messages'][0]['delivery_status'] = 'confirmed';
                $wire['data']['messages'][0]['submitted_at'] = null;
                break;
            case 'received_outbound': $wire['data']['messages'][0]['composition_state'] = 'received';
                $wire['data']['messages'][0]['delivery_status'] = null;
                $wire['data']['messages'][0]['submitted_at'] = null;
                break;
            case 'empty_visible_recipients': $wire['data']['messages'][0]['recipients'] = [];
                break;
            case 'historical_business_uuid': $wire['data']['attachments'][0]['id'] = ConversationMother::USER;
                break;
        }
        $this->expectException(\InvalidArgumentException::class);
        ConversationThreadResult::fromArray($wire);
    }

    public function test_counts_and_page_bounds_do_not_accept_a_fabricated_or_truncated_listing(): void
    {
        $invalid = [ConversationMother::listing(), ConversationMother::listing('native_cursor', []), ConversationMother::listing(null, [ConversationMother::CONVERSATION, ConversationMother::OTHER_CONVERSATION], 1)];
        $invalid[0]['data']['active_count'] = 3;
        $rejected = 0;
        foreach ($invalid as $wire) {
            try {
                ConversationListResult::fromArray($wire);
                self::fail('A native count/page inconsistency must fail.');
            } catch (\InvalidArgumentException) {
                $rejected++;
            }
        }
        self::assertSame(3, $rejected);
    }

    public function test_typed_filters_keep_native_filter_keys_and_omit_unselected_fields(): void
    {
        $filters = new Filters(mailboxId: ConversationMother::MAILBOX, targetType: 'contact', targetId: ConversationMother::RECORD, sort: 'last_message_at:desc');
        self::assertSame(['mailbox_uuid' => ConversationMother::MAILBOX, 'target_type' => 'contact', 'target_uuid' => ConversationMother::RECORD, 'sort' => 'last_message_at:desc'], $filters->toArray());
        self::assertSame(Omitted::Value, $filters->state);
        self::assertSame('{}', (new Filters)->toJson());
        self::assertSame([], (new ListConversationsRequest)->query());
        self::assertSame([], (new GetConversationRequest(ConversationMother::CONVERSATION))->query());
    }

    /** @return iterable<string,array{string}> */
    public static function invalidFilters(): iterable
    {
        yield 'array' => ['[]'];
        yield 'malformed' => ['{'];
        yield 'tenant' => ['{"company_id":77}'];
        yield 'public_id_alias' => ['{"mailbox_id":"'.ConversationMother::MAILBOX.'"}'];
        yield 'target_without_identity' => ['{"target_type":"contact"}'];
        yield 'identity_without_type' => ['{"target_uuid":"'.ConversationMother::RECORD.'"}'];
        yield 'unrecognized_state' => ['{"state":"future_hold"}'];
        yield 'blank_search' => ['{"search":"  "}'];
        yield 'invalid_utf8' => ["{\"search\":\"\xff\"}"];
        yield 'over_byte_limit' => [str_repeat(' ', 1048577).'{}'];
    }

    #[DataProvider('invalidFilters')]
    public function test_native_input_filter_validation_rejects_unknown_selection_before_http(string $wire): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ListConversationsRequest(filters: $wire);
    }

    public function test_opaque_cursor_is_measured_in_native_characters_and_raw_filters_keep_exact_whitespace(): void
    {
        $wire = " { \"state\" : \"active\", \"search\" : \"A & B\" } \n";
        $cursor = str_repeat('é', 8192);
        $request = new ListConversationsRequest(filters: $wire, cursor: $cursor, limit: 100);
        self::assertSame($wire, $request->query()['filters']);
        self::assertSame($cursor, $request->query()['cursor']);
        self::assertSame('next:/+Opaque==', $request->withCursor('next:/+Opaque==')->query()['cursor']);
        self::assertSame($cursor, $request->cursor);
        $this->expectException(\InvalidArgumentException::class);
        new GetConversationRequest(ConversationMother::CONVERSATION, cursor: $cursor.'é');
    }

    public function test_recipient_case_variations_cannot_create_a_second_visible_delivery_identity(): void
    {
        $wire = ConversationMother::message();
        $wire['recipients'] = [['email' => 'a@example.invalid', 'name' => null, 'kind' => 'to'], ['email' => 'A@example.invalid', 'name' => null, 'kind' => 'cc']];
        $this->expectException(\InvalidArgumentException::class);
        Message::fromArray($wire);
    }
}
