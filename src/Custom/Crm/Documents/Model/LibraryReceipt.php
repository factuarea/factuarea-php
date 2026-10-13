<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class LibraryReceipt
{
    private function __construct(public string $id, public int $version)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['id', 'version']);

        return new self(Wire::uuid($v['id']), Wire::integer($v['version']));
    }
}
