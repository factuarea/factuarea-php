<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Conversation extends WireModel
{
    public const SCHEMA = Schemas::HEAD;

    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'mailbox_id' => ['property' => 'mailboxId', 'type' => ['scalar' => 'string']], 'subject' => ['property' => 'subject', 'type' => ['scalar' => 'string']], 'status' => ['property' => 'status', 'type' => ['scalar' => 'string']], 'owner_id' => ['property' => 'ownerId', 'type' => ['scalar' => 'string']], 'team_id' => ['property' => 'teamId', 'type' => ['scalar' => 'string']], 'last_message_at' => ['property' => 'lastMessageAt', 'type' => ['scalar' => 'string']], 'links' => ['property' => 'links', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\ConversationLink']]]];

    /**
     * @param  list<ConversationLink>  $links
     */
    public function __construct(
        public readonly string $id,
        public readonly int $version,
        public readonly string $mailboxId,
        public readonly ?string $subject,
        public readonly string $status,
        public readonly ?string $ownerId,
        public readonly ?string $teamId,
        public readonly ?string $lastMessageAt,
        public readonly array $links,
    ) {
        $this->validate();
        if ($lastMessageAt !== null) {
            Validation::instant($lastMessageAt);
        }
        $seen = [];
        foreach ($links as $link) {
            Validation::unique($seen, $link->type.':'.strtolower($link->id));
        }
    }
}
