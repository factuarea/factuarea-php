<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Model;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;

/** An authorized server-owned association, with metadata kept omitted when masked. No URL/path is accepted. */
final readonly class Attachment
{
    /** @param array<string,int|string> $fields */
    private function __construct(public string $id, public string $documentId, public int $documentVersion, public array $fields)
    {
    }
    public static function fromWire(mixed $wire): self
    {
        $value = TicketWire::record($wire, ['id', 'document_id', 'document_version'], ['mime', 'size_bytes', 'created_by', 'created_at']);
        $fields = [];
        foreach (['mime', 'size_bytes', 'created_by', 'created_at'] as $key) {
            if (array_key_exists($key, $value)) {
                $fields[$key] = match ($key) {
                    'mime' => Wire::mime(Wire::text($value[$key], 255)),
                    'size_bytes' => Wire::integer($value[$key], true),
                    'created_by' => TicketWire::actor($value[$key]),
                    'created_at' => TicketWire::instant($value[$key]),
                };
            }
        }

        return new self(Wire::uuid($value['id']), Wire::uuid($value['document_id']), Wire::integer($value['document_version']), $fields);
    }
}
