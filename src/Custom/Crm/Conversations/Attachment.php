<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Attachment extends WireModel
{
    public const SCHEMA = Schemas::ATTACHMENT;

    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'conversation_id' => ['property' => 'conversationId', 'type' => ['scalar' => 'string']], 'message_id' => ['property' => 'messageId', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'filename' => ['property' => 'filename', 'type' => ['scalar' => 'string']], 'mime' => ['property' => 'mime', 'type' => ['scalar' => 'string']], 'size' => ['property' => 'size', 'type' => ['scalar' => 'int']], 'status' => ['property' => 'status', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly string $id,
        public readonly string $conversationId,
        public readonly string $messageId,
        public readonly int $version,
        public readonly string $filename,
        public readonly string $mime,
        public readonly int $size,
        public readonly string $status,
    ) {
        $this->validate();
        // Even ready is metadata; it does not issue a download ticket or authorize content.
    }
}
