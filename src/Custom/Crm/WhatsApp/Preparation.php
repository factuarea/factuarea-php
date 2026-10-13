<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\WhatsApp;
use InvalidArgumentException;
final readonly class Preparation
{
    /** @param array<string,mixed> $data */
    private function __construct(public array $data)
    {
    }
    public static function uuid(mixed $v): bool
    {
        return is_string($v) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD', $v) === 1;
    }
    public static function text(mixed $v, int $max): bool
    {
        return is_string($v) && $v !== '' && strlen($v) <= $max && preg_match('//u', $v) === 1;
    }
    public static function digest(mixed $v): bool
    {
        return is_string($v) && preg_match('/^[0-9a-f]{64}$/D', $v) === 1;
    }
    /** @param list<string> $keys */
    public static function keys(mixed $data, array $keys): void
    {
        if (!is_array($data) || array_diff(array_keys($data), $keys) !== [] || array_diff($keys, array_keys($data)) !== []) {
            throw new InvalidArgumentException('Invalid native WhatsApp fields.');
        }
    }
    /** @return array<string,mixed>|null */
    public static function template(mixed $v): ?array
    {
        if ($v === null) {
            return null;
        }
        self::keys($v, ['id', 'name', 'language', 'category', 'parameters']);
        if (!is_string($v['id']) || preg_match('/^[1-9][0-9]{0,63}$/D', $v['id']) !== 1 || !is_string($v['name']) || preg_match('/^[a-z][a-z0-9_]{0,511}$/D', $v['name']) !== 1 || !is_string($v['language']) || preg_match('/^[a-z]{2,3}(?:_[A-Z]{2})?$/D', $v['language']) !== 1 || !in_array($v['category'], ['MARKETING', 'UTILITY', 'AUTHENTICATION'], true) || !is_array($v['parameters']) || !array_is_list($v['parameters']) || count($v['parameters']) > 100) {
            throw new InvalidArgumentException('Invalid native WhatsApp template.');
        }
        foreach ($v['parameters'] as $parameter) {
            if (!is_string($parameter) || preg_match('//u', $parameter) !== 1 || mb_strlen($parameter) > 4096 || trim($parameter) === '' || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $parameter) === 1) {
                throw new InvalidArgumentException('Invalid template parameter.');
            }
        }

        return ['id' => $v['id'], 'name' => $v['name'], 'language' => $v['language'], 'category' => $v['category'], 'parameters' => $v['parameters']];
    }
    public static function fromWire(mixed $p, string $conversation, string $message): self
    {
        self::keys($p, ['conversation_id', 'message_id', 'effect_id', 'expected_version', 'message_version', 'purpose', 'sender', 'recipient', 'body', 'template', 'preparation_token', 'snapshot_digest', 'authorization_version']);
        if ($p['conversation_id'] !== $conversation || $p['message_id'] !== $message || !self::uuid($p['effect_id']) || !is_int($p['expected_version']) || $p['expected_version'] < 1 || !is_int($p['message_version']) || $p['message_version'] < 1 || !in_array($p['purpose'], ['contact_request', 'marketing'], true) || !self::text($p['body'], 16384) || mb_strlen($p['body']) > 4096 || !self::text($p['preparation_token'], 65536) || !self::digest($p['snapshot_digest']) || !self::text($p['authorization_version'], 255)) {
            throw new InvalidArgumentException('Invalid native WhatsApp preparation.');
        }
        foreach (['sender', 'recipient'] as $kind) {
            $a = $p[$kind];
            self::keys($a, $kind === 'sender' ? ['channel', 'phone', 'name'] : ['channel', 'phone', 'name', 'kind']);
            if ($a['channel'] !== 'whatsapp' || !is_string($a['phone']) || preg_match('/^\+[1-9][0-9]{5,14}$/D', $a['phone']) !== 1 || ($a['name'] !== null && !is_string($a['name'])) || ($kind === 'recipient' && $a['kind'] !== 'to')) {
                throw new InvalidArgumentException('Invalid native WhatsApp address.');
            }
        }
        $p['template'] = self::template($p['template']);

        return new self($p);
    }
}
