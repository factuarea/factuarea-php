<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\OAuthOriginals;
use UnexpectedValueException;
/** The exact original response is retained as a string, including its decimal and null representation. */
final readonly class OriginalReceipt
{
    private function __construct(public string $state, public string $originalBodySha256, public ?int $originalStatus,
        public ?string $originalBody, public string $authorizationVersion)
    {
    }
    public static function fromWire(mixed $envelope, OriginalIntent $intent): self
    {
        if (!is_array($envelope) || !is_array($data = $envelope['data'] ?? null) || !is_array($meta = $envelope['meta'] ?? null)
            || !in_array($data['state'] ?? null, ['confirmed', 'pending', 'in_progress', 'unconfirmed', 'missing'], true)
            || ($data['original_body_sha256'] ?? null) !== $intent->originalBodySha256
            || !is_string($meta['authorization_version'] ?? null) || $meta['authorization_version'] === ''
            || !array_key_exists('original_response', $data)) {
            throw new UnexpectedValueException('The original receipt does not match the first request.');
        }
        $original = $data['original_response'];
        if ($data['state'] === 'confirmed') {
            if (!is_array($original) || !is_int($original['status'] ?? null) || $original['status'] < 200 || $original['status'] > 299 || !is_string($original['body'] ?? null)) {
                throw new UnexpectedValueException('Original confirmation is invalid.');
            }
            json_decode($original['body'], false, 64, JSON_THROW_ON_ERROR);
        } elseif ($original !== null) {
            throw new UnexpectedValueException('An unconfirmed original cannot contain a confirmation.');
        }

        return new self($data['state'], $intent->originalBodySha256, $original['status'] ?? null, $original['body'] ?? null, $meta['authorization_version']);
    }
}
