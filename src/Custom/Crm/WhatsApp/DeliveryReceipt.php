<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\WhatsApp;
use InvalidArgumentException;
final readonly class DeliveryReceipt
{
    private function __construct(public string $status, public ?string $providerMessageId, public ?string $submittedAt, public ?string $reasonCode)
    {
    }
    public static function fromWire(mixed $r): self
    {
        Preparation::keys($r, ['status', 'provider_message_id', 'submitted_at', 'reason_code']);
        $date = $r['submitted_at'] === null ? null : (is_string($r['submitted_at']) ? \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s\Z', $r['submitted_at'], new \DateTimeZone('UTC')) : false);
        if (!in_array($r['status'], ['confirmed', 'unconfirmed', 'failed'], true) || ($r['provider_message_id'] !== null && (!Preparation::text($r['provider_message_id'], 4096) || mb_strlen($r['provider_message_id']) > 1024 || trim($r['provider_message_id']) === '' || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $r['provider_message_id']) === 1)) || ($r['submitted_at'] !== null && (!$date || $date->format('Y-m-d\TH:i:s\Z') !== $r['submitted_at'])) || ($r['reason_code'] !== null && (!is_string($r['reason_code']) || preg_match('/^[a-z][a-z0-9_]{0,63}$/D', $r['reason_code']) !== 1)) || ($r['status'] === 'confirmed' && $r['submitted_at'] === null) || ($r['status'] === 'failed' && ($r['provider_message_id'] !== null || $r['submitted_at'] !== null || $r['reason_code'] === null))) {
            throw new InvalidArgumentException('Invalid native WhatsApp receipt.');
        }

        return new self($r['status'], $r['provider_message_id'], $r['submitted_at'], $r['reason_code']);
    }
}
