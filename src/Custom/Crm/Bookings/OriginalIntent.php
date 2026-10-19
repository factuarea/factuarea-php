<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Schema;

/** Immutable original intent; all entity/effect IDs are assigned by the native server. */
final readonly class OriginalIntent
{
    private function __construct(public string $operation, #[\SensitiveParameter] public string $originalWire,
        #[\SensitiveParameter] public string $originalKey, public ?string $serviceId, public ?int $expectedVersion,
        public ?string $publicId, public ?int $publicationVersion, public ?string $bookingId,
        public ?string $startAt, public ?string $endAt)
    {
    }

    public static function administrative(#[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key, ?string $serviceId = null): self
    {
        WireCodec::key($key, false);
        $input = WireCodec::object($wire);
        Schema::validate(Schemas::WRITE, $input);
        $expected = $input->expected_version ?? null;
        if ($serviceId === null) {
            if ($expected !== null) {
                throw new \InvalidArgumentException('Native service creation omits or nulls its CAS and accepts no caller ID.');
            }
        } else {
            WireCodec::id($serviceId);
            if (! is_int($expected)) {
                throw new \InvalidArgumentException('A service update requires its original positive CAS.');
            }
            WireCodec::version($expected);
        }
        $definition = $input->definition ?? null;
        if (! $definition instanceof \stdClass) {
            throw new \InvalidArgumentException('The native service definition is required.');
        }
        if (property_exists($definition, 'required_sources') && (! is_array($definition->required_sources)
            || ! in_array('booking', $definition->required_sources, true))) {
            throw new \InvalidArgumentException('The native required_sources must retain booking.');
        }
        if (property_exists($definition, 'schedule')) {
            $schedule = $definition->schedule;
            if (! $schedule instanceof \stdClass || ! is_string($schedule->timezone ?? null)
                || ! in_array($schedule->timezone, \DateTimeZone::listIdentifiers(\DateTimeZone::ALL_WITH_BC), true)) {
                throw new \InvalidArgumentException('The native schedule timezone must be explicit.');
            }
        }

        return new self('booking.save_service', $wire, $key, $serviceId, $expected, null, null, null, null, null);
    }

    public static function createPublic(#[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key,
        string $publicId, int $publicationVersion): self
    {
        return self::publicIntent('booking.create_public', $wire, $key, $publicId, $publicationVersion, null);
    }

    public static function reschedulePublic(#[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key,
        string $publicId, int $publicationVersion, string $bookingId): self
    {
        return self::publicIntent('booking.reschedule_public', $wire, $key, $publicId, $publicationVersion, $bookingId);
    }

    public static function cancelPublic(#[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key,
        string $publicId, int $publicationVersion, string $bookingId): self
    {
        return self::publicIntent('booking.cancel_public', $wire, $key, $publicId, $publicationVersion, $bookingId);
    }

    private static function publicIntent(string $operation, #[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key,
        string $publicId, int $publicationVersion, ?string $bookingId): self
    {
        WireCodec::key($key, true);
        WireCodec::publicId($publicId);
        WireCodec::version($publicationVersion);
        $input = WireCodec::object($wire);
        $schema = match ($operation) {
            'booking.create_public' => Schemas::PUBLIC_CREATE,
            'booking.reschedule_public' => Schemas::PUBLIC_RESCHEDULE,
            'booking.cancel_public' => Schemas::PUBLIC_CANCEL,
            default => throw new \InvalidArgumentException('An original native public Booking operation is required.'),
        };
        Schema::validate($schema, $input);
        $expected = null;
        if ($bookingId !== null) {
            WireCodec::id($bookingId);
            $expected = $input->expected_version ?? null;
            if (! is_int($expected)) {
                throw new \InvalidArgumentException('The original booking CAS is required.');
            }
            WireCodec::version($expected);
        }
        $slot = $operation === 'booking.create_public' ? ($input->slot ?? null) : ($input->new_slot ?? null);
        $start = null;
        $end = null;
        if ($slot !== null) {
            if (! $slot instanceof \stdClass || ! is_string($slot->start_at ?? null) || ! is_string($slot->end_at ?? null)) {
                throw new \InvalidArgumentException('The original native slot is required.');
            }
            $start = $slot->start_at;
            $end = $slot->end_at;
            WireCodec::interval($start, $end);
        }

        return new self($operation, $wire, $key, null, $expected, $publicId, $publicationVersion, $bookingId, $start, $end);
    }

    /** @return array<string,string|int|null> */
    public function __debugInfo(): array
    {
        return ['operation' => $this->operation, 'serviceId' => $this->serviceId, 'expectedVersion' => $this->expectedVersion,
            'publicId' => $this->publicId, 'publicationVersion' => $this->publicationVersion, 'bookingId' => $this->bookingId,
            'originalWire' => '[redacted]', 'originalKey' => '[redacted]'];
    }
}
