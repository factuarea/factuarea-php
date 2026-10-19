<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ConversationThread extends WireModel
{
    public const SCHEMA = Schemas::THREAD;

    public const FIELDS = ['conversation' => ['property' => 'conversation', 'type' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\Conversation']], 'messages' => ['property' => 'messages', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\Message']]], 'attachments' => ['property' => 'attachments', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\Attachment']]], 'next_cursor' => ['property' => 'nextCursor', 'type' => ['scalar' => 'string']], 'limit' => ['property' => 'limit', 'type' => ['scalar' => 'int']]];

    /**
     * @param  list<Message>  $messages
     * @param  list<Attachment>  $attachments
     */
    public function __construct(
        public readonly Conversation $conversation,
        public readonly array $messages,
        public readonly array $attachments,
        public readonly ?string $nextCursor,
        public readonly int $limit,
    ) {
        $this->validate();
        if (count($messages) > $limit || ($nextCursor !== null && $messages === [])) {
            throw new \InvalidArgumentException('Native thread page metadata is inconsistent.');
        }
        if ($nextCursor !== null) {
            Validation::cursor($nextCursor);
        }
        $seenMessages = [];
        foreach ($messages as $message) {
            if (strcasecmp($message->conversationId, $conversation->id) !== 0) {
                throw new \InvalidArgumentException('A message belongs to another native thread.');
            }
            Validation::unique($seenMessages, $message->id);
        }
        $seenAttachments = [];
        foreach ($attachments as $attachment) {
            if (strcasecmp($attachment->conversationId, $conversation->id) !== 0 || ! isset($seenMessages[strtolower($attachment->messageId)])) {
                throw new \InvalidArgumentException('An attachment is not metadata of a message in this native page.');
            }
            Validation::unique($seenAttachments, $attachment->id);
        }
    }
}
