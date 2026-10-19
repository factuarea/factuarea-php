<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Message extends WireModel
{
    public const SCHEMA = Schemas::MESSAGE;

    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'conversation_id' => ['property' => 'conversationId', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'direction' => ['property' => 'direction', 'type' => ['scalar' => 'string']], 'sender' => ['property' => 'sender', 'type' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\Participant']], 'recipients' => ['property' => 'recipients', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\Recipient']]], 'subject' => ['property' => 'subject', 'type' => ['scalar' => 'string']], 'body' => ['property' => 'body', 'type' => ['scalar' => 'string']], 'body_format' => ['property' => 'bodyFormat', 'type' => ['scalar' => 'string']], 'composition_state' => ['property' => 'compositionState', 'type' => ['scalar' => 'string']], 'delivery_status' => ['property' => 'deliveryStatus', 'type' => ['scalar' => 'string']], 'submitted_at' => ['property' => 'submittedAt', 'type' => ['scalar' => 'string']]];

    /**
     * @param  list<Recipient>|null  $recipients
     */
    public function __construct(
        public readonly string $id,
        public readonly string $conversationId,
        public readonly int $version,
        public readonly string $direction,
        public readonly ?Participant $sender,
        public readonly ?array $recipients,
        public readonly ?string $subject,
        public readonly ?string $body,
        public readonly string $bodyFormat,
        public readonly string $compositionState,
        public readonly ?string $deliveryStatus,
        public readonly ?string $submittedAt,
    ) {
        $this->validate();
        if ($submittedAt !== null) {
            Validation::instant($submittedAt);
        }
        if (in_array($direction, ['inbound', 'outbound'], true) && in_array($compositionState, ['draft', 'send_intent', 'received'], true)
            && (($direction === 'inbound') !== ($compositionState === 'received'))
            || in_array($compositionState, ['draft', 'received'], true) && ($deliveryStatus !== null || $submittedAt !== null)
            || ($deliveryStatus === 'confirmed' && $submittedAt === null)
            || (in_array($deliveryStatus, [null, 'pending', 'failed'], true) && $submittedAt !== null)) {
            throw new \InvalidArgumentException('Native delivery facts are inconsistent; intent does not confirm sending.');
        }
        if ($recipients !== null) {
            if ($recipients === [] || count($recipients) > 100) {
                throw new \InvalidArgumentException('Native recipients are invalid.');
            }
            $seen = [];
            $hasTo = false;
            $known = true;
            foreach ($recipients as $recipient) {
                Validation::unique($seen, strtolower($recipient->email));
                $hasTo = $hasTo || $recipient->kind === 'to';
                $known = $known && in_array($recipient->kind, ['to', 'cc', 'bcc'], true);
            }
            if ($known && ! $hasTo) {
                throw new \InvalidArgumentException('Native recipient list has no To recipient.');
            }
        }
    }
}
