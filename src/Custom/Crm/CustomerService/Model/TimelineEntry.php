<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Model;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

final readonly class TimelineEntry
{
    /** @param array<string,string|Ticket> $fields */
    private function __construct(public int $version, public array $fields)
    {
    }
    public static function fromWire(mixed $wire, string $ticketId): self
    {
        $value = TicketWire::record($wire, ['version'], ['action', 'actor_id', 'occurred_at', 'snapshot']);
        $version = Wire::integer($value['version']);
        $fields = [];
        foreach (['action', 'actor_id', 'occurred_at', 'snapshot'] as $key) {
            if (array_key_exists($key, $value)) {
                $fields[$key] = match ($key) {
                    'action' => TicketWire::enumText($value[$key]),
                    'actor_id' => TicketWire::actor($value[$key]),
                    'occurred_at' => TicketWire::instant($value[$key]),
                    'snapshot' => Ticket::fromWire($value[$key]),
                };
            }
        }
        if (isset($fields['snapshot']) && $fields['snapshot'] instanceof Ticket
            && (strcasecmp($fields['snapshot']->id, $ticketId) !== 0 || $fields['snapshot']->version !== $version)) {
            throw new InvalidArgumentException('A timeline snapshot must retain its parent and historical version.');
        }

        return new self($version, $fields);
    }
}
