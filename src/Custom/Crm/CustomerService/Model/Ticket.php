<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Model;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

/** Optional projection keys preserve omission versus null. Absent attachments never mean an empty authorized list. */
final readonly class Ticket
{
    /** @param array<string,mixed> $fields */
    private function __construct(public string $id, public int $version, public array $fields)
    {
    }
    public static function fromWire(mixed $wire): self
    {
        $optional = ['title', 'description', 'status', 'priority', 'type', 'creator_id', 'pipeline_id', 'stage_id', 'counterparty_id', 'person_id', 'assignee_id',
            'created_at', 'updated_at', 'first_response_at', 'resolved_at', 'closed_at', 'archived_at', 'cycle', 'waiting_reason', 'comments', 'attachments', 'writable_fields', 'authorization_version'];
        $value = TicketWire::record($wire, ['id', 'version'], $optional);
        $fields = [];
        foreach ($optional as $key) {
            if (!array_key_exists($key, $value)) {
                continue;
            }
            $fields[$key] = match ($key) {
                'title' => Wire::text($value[$key], 255),
                'description' => Wire::text($value[$key], 20000, true),
                'creator_id' => TicketWire::actor($value[$key]),
                'assignee_id' => $value[$key] === null ? null : TicketWire::actor($value[$key]),
                'pipeline_id', 'stage_id', 'counterparty_id', 'person_id' => $value[$key] === null ? null : Wire::uuid($value[$key]),
                'created_at', 'updated_at' => TicketWire::instant($value[$key]),
                'first_response_at', 'resolved_at', 'closed_at', 'archived_at' => $value[$key] === null ? null : TicketWire::instant($value[$key]),
                'cycle' => Wire::integer($value[$key]),
                'waiting_reason' => $value[$key] === null ? null : TicketWire::enumText($value[$key]),
                'comments' => array_map(Comment::fromWire(...), Wire::items($value[$key])),
                'attachments' => array_map(Attachment::fromWire(...), Wire::items($value[$key])),
                'writable_fields' => self::writableFields($value[$key]),
                'authorization_version' => Wire::text($value[$key], 4096),
                default => TicketWire::enumText($value[$key]),
            };
        }
        if (array_key_exists('writable_fields', $fields) !== array_key_exists('authorization_version', $fields)) {
            throw new InvalidArgumentException('Ticket write fields require the native authorization version.');
        }

        return new self(Wire::uuid($value['id']), Wire::integer($value['version']), $fields);
    }
    public function hasField(string $field): bool
    {
        return array_key_exists($field, $this->fields);
    }
    /** @return list<Attachment>|null null means masked/omitted. */
    public function attachments(): ?array
    {
        if (!array_key_exists('attachments', $this->fields)) {
            return null;
        }
        /** @var list<Attachment> $attachments */
        $attachments = $this->fields['attachments'];

        return $attachments;
    }
    /** @return list<string> */
    private static function writableFields(mixed $wire): array
    {
        $fields = array_map(TicketWire::enumText(...), Wire::items($wire));
        if (count(array_unique($fields)) !== count($fields)) {
            throw new InvalidArgumentException('Ticket writable fields must be unique.');
        }

        return $fields;
    }
}
