<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use UnexpectedValueException;

/** Preserves forward-compatible fields/enums and decimal strings; masked omission differs from explicit null. */
final readonly class SalesResult
{
    private function __construct(public string $id, public ?int $version, public \stdClass $data, public string $rawBody,
        public int $status, public ?string $requestId, public bool $confirmed)
    {
    }
    public static function decode(string $kind, string $raw, int $status, ?string $requestId): self
    {
        $root = json_decode($raw, false, 64, JSON_THROW_ON_ERROR);
        $data = $root->data ?? null;
        if (!$root instanceof \stdClass || !$data instanceof \stdClass) {
            throw new UnexpectedValueException('The native CRM projection requires data.');
        }
        $id = Wire::uuid($kind === 'activity' ? ($data->activity_id ?? '') : ($data->id ?? ''));
        $version = property_exists($data, 'version') ? $data->version : null;
        if ($version !== null && (!is_int($version) || $version < 1)) {
            throw new UnexpectedValueException('Invalid native version.');
        }
        if ($kind === 'opportunity' && $version === null) {
            throw new UnexpectedValueException('The opportunity version is required.');
        }
        foreach (['amount', 'probability', 'probability_override', 'weighted_value'] as $field) {
            if (property_exists($data, $field) && $data->{$field} !== null && !is_string($data->{$field})) {
                throw new UnexpectedValueException('Native decimals remain strings.');
            }
        }
        $confirmed = true;
        if ($kind === 'activity') {
            Wire::uuid($data->effect_id ?? '');
            if ($data->effect_id === $id || !in_array($data->state ?? null, ['confirmed', 'pending', 'in_progress', 'unconfirmed'], true)
                || !property_exists($data, 'activity') || ($data->state !== 'confirmed' && $data->activity !== null)) {
                throw new UnexpectedValueException('Invalid native activity state.');
            }
            $confirmed = $data->state === 'confirmed';
            if ($data->activity !== null && (!$data->activity instanceof \stdClass || ($data->activity->id ?? null) !== $id)) {
                throw new UnexpectedValueException('Activity representation identity mismatch.');
            }
        } elseif ($kind === 'lead' && $version === null && (($data->confirmed ?? null) !== true || ($data->representation_available ?? null) !== false)) {
            throw new UnexpectedValueException('A masked lead needs the original confirmed identity receipt.');
        }

        return new self($id, $version, $data, $raw, $status, $requestId, $confirmed);
    }
    public function field(string $name): mixed
    {
        return property_exists($this->data, $name) ? $this->data->{$name} : Omitted::Value;
    }
}
