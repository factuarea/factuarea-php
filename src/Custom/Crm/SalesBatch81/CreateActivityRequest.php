<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use InvalidArgumentException;

final readonly class CreateActivityRequest
{
    public function __construct(public string $type, public string $title, public string $targetType, public string $targetId,
        public string $timezone, public string|null|Omitted $body = Omitted::Value, public string|null|Omitted $ownerId = Omitted::Value,
        public string|null|Omitted $dueAt = Omitted::Value)
    {
        if (!in_array($type, ['task', 'call', 'meeting', 'note'], true) || !in_array($targetType, ['contact', 'person', 'lead', 'opportunity', 'booking'], true)) {
            throw new InvalidArgumentException('An explicit native activity type and target are required.');
        }
        Wire::text($title, 255, true);
        Wire::uuid($targetId);
        Wire::text($timezone, 100, true);
        if (!in_array($timezone, \DateTimeZone::listIdentifiers(\DateTimeZone::ALL_WITH_BC), true) && $timezone !== 'UTC') {
            throw new InvalidArgumentException('An explicit IANA timezone is required.');
        }
        if (!$body instanceof Omitted) {
            Wire::text($body, 65535);
        }
        if (is_string($ownerId)) {
            Wire::uuid($ownerId, true);
        }
        if (is_string($dueAt)) {
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s\Z', $dueAt, new \DateTimeZone('UTC'));
            if (!$date || $date->format('Y-m-d\TH:i:s\Z') !== $dueAt) {
                throw new InvalidArgumentException('An exact UTC due_at is required.');
            }
        }
    }
    public function toArray(): array
    {
        return ['type' => $this->type, 'title' => $this->title, 'target' => ['type' => $this->targetType, 'id' => $this->targetId],
            'timezone' => $this->timezone] + Wire::optional(['body' => $this->body, 'owner_id' => $this->ownerId, 'due_at' => $this->dueAt]);
    }
}
