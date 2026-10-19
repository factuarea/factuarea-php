<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

/** Persist this tuple before sending: no effect or new binding identity is generated in the SDK. */
final readonly class OriginalIntent
{
    private function __construct(public string $action, public string $ticketId, public string $taskId,
        public int $expectedTicketVersion, public int $expectedTaskVersion, public ?string $bindingId,
        public ?int $expectedBindingVersion, public string $originalWire, public string $originalKey)
    {
    }

    public static function link(string $ticketId, string $originalWire, string $originalKey): self
    {
        return self::create('link', $ticketId, $originalWire, $originalKey);
    }

    public static function unlink(string $ticketId, string $originalWire, string $originalKey): self
    {
        return self::create('unlink', $ticketId, $originalWire, $originalKey);
    }

    private static function create(string $action, string $ticketId, string $wire, string $key): self
    {
        WireCodec::id($ticketId);
        WireCodec::key($key);
        $value = WireCodec::intent($action, $wire);

        return new self($action, $ticketId, $value->task_id, $value->expected_ticket_version, $value->expected_task_version,
            $value->binding_id ?? null, $value->expected_binding_version ?? null, $wire, $key);
    }
}
