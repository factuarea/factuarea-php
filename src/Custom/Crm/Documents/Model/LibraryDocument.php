<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;

/** Masked fields stay omitted; an explicit archive/share null stays null. */
final readonly class LibraryDocument
{
    /** @param list<array{number:int,mime:string,size_bytes:int,created_by_id:string,created_at:string}>|Omitted $versions
     * @param list<array{entity_type:string,entity_id:string,document_version:int,linked_by_id:string,linked_at:string}>|Omitted $links
     * @param list<array{id:string,grant_type:string,grantee_id:string,expires_at:?string,revoked_at:?string,revoked_by_id:?string,created_by_id:string,created_at:string}>|Omitted $shares */
    private function __construct(public string $id, public int $version, public string|Omitted $title, public string|Omitted $visibility,
        public string|Omitted $createdById, public string|null|Omitted $archivedAt, public string|null|Omitted $archivedById,
        public array|Omitted $versions, public array|Omitted $links, public array|Omitted $shares)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['id', 'version'], ['title', 'visibility', 'created_by_id', 'archived_at', 'archived_by_id', 'versions', 'links', 'shares']);
        $versions = Omitted::Value;
        $links = Omitted::Value;
        $shares = Omitted::Value;
        if (array_key_exists('versions', $v)) {
            $versions = [];
            foreach (Wire::items($v['versions']) as $item) {
                $r = Wire::record($item, ['number', 'mime', 'size_bytes', 'created_by_id', 'created_at']);
                $versions[] = ['number' => Wire::integer($r['number']), 'mime' => Wire::mime(Wire::text($r['mime'], 255)), 'size_bytes' => Wire::integer($r['size_bytes'], true), 'created_by_id' => Wire::uuid($r['created_by_id'], true), 'created_at' => Wire::timestamp($r['created_at'])];
            }
        }
        if (array_key_exists('links', $v)) {
            $links = [];
            foreach (Wire::items($v['links']) as $item) {
                $r = Wire::record($item, ['entity_type', 'entity_id', 'document_version', 'linked_by_id', 'linked_at']);
                $links[] = ['entity_type' => Wire::text($r['entity_type'], 100), 'entity_id' => Wire::uuid($r['entity_id']), 'document_version' => Wire::integer($r['document_version']), 'linked_by_id' => Wire::uuid($r['linked_by_id'], true), 'linked_at' => Wire::timestamp($r['linked_at'])];
            }
        }
        if (array_key_exists('shares', $v)) {
            $shares = [];
            foreach (Wire::items($v['shares']) as $item) {
                $r = Wire::record($item, ['id', 'grant_type', 'grantee_id', 'expires_at', 'revoked_at', 'revoked_by_id', 'created_by_id', 'created_at']);
                $shares[] = ['id' => Wire::uuid($r['id']), 'grant_type' => Wire::text($r['grant_type'], 100), 'grantee_id' => Wire::uuid($r['grantee_id'], true),
                    'expires_at' => $r['expires_at'] === null ? null : Wire::timestamp($r['expires_at']), 'revoked_at' => $r['revoked_at'] === null ? null : Wire::timestamp($r['revoked_at']),
                    'revoked_by_id' => $r['revoked_by_id'] === null ? null : Wire::uuid($r['revoked_by_id'], true), 'created_by_id' => Wire::uuid($r['created_by_id'], true), 'created_at' => Wire::timestamp($r['created_at'])];
            }
        }

        return new self(Wire::uuid($v['id']), Wire::integer($v['version']), array_key_exists('title', $v) ? Wire::text($v['title'], 255) : Omitted::Value,
            array_key_exists('visibility', $v) ? Wire::text($v['visibility'], 100) : Omitted::Value, array_key_exists('created_by_id', $v) ? Wire::uuid($v['created_by_id'], true) : Omitted::Value,
            array_key_exists('archived_at', $v) ? ($v['archived_at'] === null ? null : Wire::timestamp($v['archived_at'])) : Omitted::Value,
            array_key_exists('archived_by_id', $v) ? ($v['archived_by_id'] === null ? null : Wire::uuid($v['archived_by_id'], true)) : Omitted::Value, $versions, $links, $shares);
    }
}
