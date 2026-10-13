<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Model;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;

/** Current ACL determines comment visibility and each optional field, including historical snapshots. */
final readonly class Comment
{
    /** @param array<string,string|null> $fields */
    private function __construct(public string $id, public string $visibility, public array $fields)
    {
    }
    public static function fromWire(mixed $wire): self
    {
        $optional = ['author_type', 'author_id', 'body', 'body_format', 'delivery_state', 'conversation_message_id', 'created_at', 'edited_at'];
        $value = TicketWire::record($wire, ['id', 'visibility'], $optional);
        $fields = [];
        foreach ($optional as $key) {
            if (array_key_exists($key, $value)) {
                $fields[$key] = match ($key) {
                    'author_id' => TicketWire::actor($value[$key]),
                    'body' => Wire::text($value[$key], 20000),
                    'conversation_message_id' => $value[$key] === null ? null : Wire::uuid($value[$key]),
                    'created_at' => TicketWire::instant($value[$key]),
                    'edited_at' => $value[$key] === null ? null : TicketWire::instant($value[$key]),
                    default => TicketWire::enumText($value[$key]),
                };
            }
        }

        return new self(Wire::uuid($value['id']), TicketWire::enumText($value['visibility']), $fields);
    }
}
