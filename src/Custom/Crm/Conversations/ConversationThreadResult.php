<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ConversationThreadResult extends WireModel
{
    public const SCHEMA = Schemas::THREAD_RESULT;

    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\ConversationThread']], 'meta' => ['property' => 'meta', 'type' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\ReadMeta']]];

    public function __construct(
        public readonly ConversationThread $data,
        public readonly ReadMeta $meta,
    ) {
        $this->validate();
        Validation::headMask($data->conversation, $meta);
        if (! in_array('messages', $meta->readableFields, true) && ($data->messages !== [] || $data->attachments !== [] || $data->nextCursor !== null)
            || ! in_array('attachments', $meta->readableFields, true) && $data->attachments !== []) {
            throw new \InvalidArgumentException('Native hidden thread collections were not masked.');
        }
        foreach ($data->messages as $message) {
            foreach (['sender' => $message->sender, 'recipients' => $message->recipients, 'subject' => $message->subject, 'body' => $message->body,
                'delivery_status' => $message->deliveryStatus, 'submitted_at' => $message->submittedAt] as $field => $value) {
                $mask = $field === 'submitted_at' ? 'delivery_status' : $field;
                if (! in_array($mask, $meta->readableFields, true) && $value !== null) {
                    throw new \InvalidArgumentException('A hidden native message field was not masked.');
                }
            }
        }
    }
}
