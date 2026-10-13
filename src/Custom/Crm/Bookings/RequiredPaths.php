<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

/** Explicit native relative paths, selected for ONE actual credential/base-URL surface. */
final readonly class RequiredPaths
{
    /** @param array<string,string> $paths */
    private function __construct(public string $surface, private array $paths)
    {
        $placeholders = $surface === 'administrative'
            ? ['list_services' => null, 'list_bookings' => null, 'get_service' => 'service', 'create_service' => null,
                'save_service' => 'service', 'recover_create' => null, 'recover_save' => 'service']
            : ['configuration' => 'publicId', 'availability' => 'publicId', 'create' => 'publicId',
                'reschedule' => null, 'cancel' => null, 'receipt' => 'publicId', 'management' => 'publicId'];
        if (array_keys($paths) !== array_keys($placeholders)) {
            throw new \InvalidArgumentException('Supply the complete native Booking path set for this surface.');
        }
        foreach ($paths as $kind => $path) {
            if ($path === '' || preg_match('~^[A-Za-z0-9_{}./-]+$~D', $path) !== 1 || $path[0] === '/'
                || str_contains($path, '//') || array_intersect(explode('/', $path), ['.', '..']) !== []) {
                throw new \InvalidArgumentException('Supply a native relative path without URI, query or traversal.');
            }
            $placeholder = $placeholders[$kind];
            $expected = $placeholder === null ? 0 : 1;
            if (substr_count($path, '{') !== $expected || substr_count($path, '}') !== $expected
                || ($placeholder !== null && ! str_contains($path, '{'.$placeholder.'}'))) {
                throw new \InvalidArgumentException('Use only the exact native Booking route placeholder.');
            }
        }
    }

    public static function administrative(string $listServices, string $listBookings, string $getService,
        string $createService, string $saveService, string $recoverCreate, string $recoverSave): self
    {
        return new self('administrative', ['list_services' => $listServices, 'list_bookings' => $listBookings,
            'get_service' => $getService, 'create_service' => $createService, 'save_service' => $saveService,
            'recover_create' => $recoverCreate, 'recover_save' => $recoverSave]);
    }

    public static function publicBooking(string $configuration, string $availability, string $create,
        string $reschedule, string $cancel, string $receipt, string $management): self
    {
        return new self('public_booking', ['configuration' => $configuration, 'availability' => $availability,
            'create' => $create, 'reschedule' => $reschedule, 'cancel' => $cancel, 'receipt' => $receipt, 'management' => $management]);
    }

    public function path(string $kind, ?string $id = null): string
    {
        $path = $this->paths[$kind] ?? throw new \InvalidArgumentException('This operation is outside the supplied native surface.');
        $placeholder = $this->surface === 'administrative' ? 'service' : 'publicId';
        if (str_contains($path, '{'.$placeholder.'}')) {
            if ($id === null) {
                throw new \InvalidArgumentException('The native route target is required.');
            }
            $this->surface === 'administrative' ? WireCodec::id($id) : WireCodec::publicId($id);

            return str_replace('{'.$placeholder.'}', $id, $path);
        }
        if ($id !== null) {
            throw new \InvalidArgumentException('This route accepts no entity target.');
        }

        return $path;
    }
}
