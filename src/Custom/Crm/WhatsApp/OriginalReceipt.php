<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\WhatsApp;
use InvalidArgumentException;
final readonly class OriginalReceipt
{
    /** @param array<string,mixed> $data */
    private function __construct(public array $data, public ?DeliveryReceipt $receipt)
    {
    }
    public static function fromWire(mixed $r, SendIntent $intent): self
    {
        Preparation::keys($r, ['state', 'conversation_id', 'message_id', 'effect_id', 'original_wire_hash', 'preparation_digest', 'receipt']);
        $p = $intent->preparation->data;
        foreach (['conversation_id', 'message_id', 'effect_id'] as $key) {
            if ($r[$key] !== $p[$key]) {
                throw new InvalidArgumentException('Native original identity changed.');
            }
        }
        if ($r['original_wire_hash'] !== $intent->originalWireHash || $r['preparation_digest'] !== $p['snapshot_digest'] || !in_array($r['state'], ['not_found', 'confirmed', 'unconfirmed', 'failed'], true)) {
            throw new InvalidArgumentException('Native original does not match its retained intent.');
        }
        $receipt = $r['receipt'] === null ? null : DeliveryReceipt::fromWire($r['receipt']);
        if ($r['state'] === 'not_found' ? $receipt !== null : $receipt?->status !== $r['state']) {
            throw new InvalidArgumentException('Native original has an inconsistent receipt.');
        }

        return new self($r, $receipt);
    }
}
