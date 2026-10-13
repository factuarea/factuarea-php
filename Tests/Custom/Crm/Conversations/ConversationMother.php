<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Conversations;

/** Offline public wire fixtures, without a backend issuer, grants or native database state.
 * @phpstan-type Head array{id:string,version:int,mailbox_id:string,subject:?string,status:string,owner_id:?string,team_id:?string,last_message_at:?string,links:list<array{type:string,id:string,state:string}>}
 * @phpstan-type Meta array{authorization_version:string,readable_fields:list<string>,filterable_fields:list<string>,sortable_fields:list<string>}
 * @phpstan-type MessageWire array{id:string,conversation_id:string,version:int,direction:string,sender:?array{email:string,name:?string},recipients:?list<array{email:string,name:?string,kind:string}>,subject:?string,body:?string,body_format:string,composition_state:string,delivery_status:?string,submitted_at:?string}
 * @phpstan-type AttachmentWire array{id:string,conversation_id:string,message_id:string,version:int,filename:string,mime:string,size:int,status:string}
 * @phpstan-type ListWire array{data:array{items:list<Head>,next_cursor:?string,total:int,active_count:int,archived_count:int,limit:int},meta:Meta}
 * @phpstan-type ThreadWire array{data:array{conversation:Head,messages:list<MessageWire>,attachments:list<AttachmentWire>,next_cursor:?string,limit:int},meta:Meta}
 */
final class ConversationMother
{
    public const CONVERSATION = '019a4afd-7500-7001-8000-000000000001';
    public const OTHER_CONVERSATION = '019a4afd-7500-7002-8000-000000000002';
    public const MAILBOX = '019a4afd-7500-7003-8000-000000000003';
    public const MESSAGE = '019a4afd-7500-7004-8000-000000000004';
    public const OTHER_MESSAGE = '019a4afd-7500-7005-8000-000000000005';
    public const ATTACHMENT = '019a4afd-7500-7006-8000-000000000006';
    public const OTHER_ATTACHMENT = '019a4afd-7500-7007-8000-000000000007';
    public const RECORD = '019a4afd-7500-7008-8000-000000000008';
    public const USER = '49c08c84-8dc1-4fd5-8a7f-71cb3ac90b42';
    public const AT = '2026-10-10T12:30:01Z';
    public const C7 = 'native_conversation_fixture:C7:75';

    /** @return Head */
    public static function head(string $id = self::CONVERSATION): array
    {
        return ['id' => $id, 'version' => 9, 'mailbox_id' => self::MAILBOX, 'subject' => 'Seguimiento & consulta',
            'status' => 'active', 'owner_id' => self::USER, 'team_id' => null, 'last_message_at' => self::AT,
            'links' => [['type' => 'contact', 'id' => self::RECORD, 'state' => 'confirmed']]];
    }

    /**
     * @param  ?list<string>  $readable
     * @return Meta
     */
    public static function meta(?array $readable = null): array
    {
        return ['authorization_version' => self::C7, 'readable_fields' => $readable ?? ['uuid', 'version', 'mailbox_uuid', 'status',
            'subject', 'owner_uuid', 'team_uuid', 'last_message_at', 'links', 'messages', 'sender', 'recipients', 'body', 'delivery_status', 'attachments'],
            'filterable_fields' => ['mailbox_uuid', 'status', 'subject', 'links'], 'sortable_fields' => ['last_message_at', 'subject', 'uuid']];
    }

    /** @return MessageWire */
    public static function message(string $id = self::MESSAGE): array
    {
        return ['id' => $id, 'conversation_id' => self::CONVERSATION, 'version' => 3, 'direction' => 'outbound',
            'sender' => ['email' => 'sender@example.invalid', 'name' => null],
            'recipients' => [['email' => 'recipient@example.invalid', 'name' => 'Recipient', 'kind' => 'to']],
            'subject' => 'Seguimiento', 'body' => "Texto sin interpretar.\n{{caller_text}}", 'body_format' => 'plain_text',
            'composition_state' => 'send_intent', 'delivery_status' => 'unconfirmed', 'submitted_at' => self::AT];
    }

    /** @return AttachmentWire */
    public static function attachment(string $id = self::ATTACHMENT, string $messageId = self::MESSAGE): array
    {
        return ['id' => $id, 'conversation_id' => self::CONVERSATION, 'message_id' => $messageId, 'version' => 1,
            'filename' => 'resumen.txt', 'mime' => 'text/plain', 'size' => 901, 'status' => 'quarantine'];
    }

    /**
     * @param  list<string>  $ids
     * @return ListWire
     */
    public static function listing(?string $cursor = null, array $ids = [self::CONVERSATION], int $limit = 20): array
    {
        return ['data' => ['items' => array_map(self::head(...), $ids), 'next_cursor' => $cursor,
            'total' => 2, 'active_count' => 1, 'archived_count' => 1, 'limit' => $limit], 'meta' => self::meta()];
    }

    /** @return ThreadWire */
    public static function thread(?string $cursor = null, string $messageId = self::MESSAGE, string $attachmentId = self::ATTACHMENT, int $limit = 20): array
    {
        return ['data' => ['conversation' => self::head(), 'messages' => [self::message($messageId)],
            'attachments' => [self::attachment($attachmentId, $messageId)], 'next_cursor' => $cursor, 'limit' => $limit], 'meta' => self::meta()];
    }
}
