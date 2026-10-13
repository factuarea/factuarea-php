<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

/** Immutable original bytes and opaque key. Identity is allocated by the native server. */
final readonly class OriginalIntent
{
    private function __construct(public string $operation, public ?string $targetId, public int $expectedVersion,
        public string $originalWire, public string $originalKey, public ?string $subjectType, public ?string $subjectId)
    {
    }

    public static function save(string $wire, string $originalKey, ?string $sequenceId = null): self
    {
        WireCodec::key($originalKey);
        if ($sequenceId !== null) {
            WireCodec::id($sequenceId);
        }
        $values = WireCodec::object($wire, ['expected_version', 'definition']);
        $version = WireCodec::version($values->expected_version, $sequenceId === null ? 0 : 1);
        if ($sequenceId === null && $version !== 0) {
            throw new \InvalidArgumentException('Create requires expected_version zero and no client identity.');
        }
        WireCodec::definition($values->definition);

        return new self('save', $sequenceId, $version, $wire, $originalKey, null, null);
    }

    public static function enrol(string $sequenceId, string $wire, string $originalKey): self
    {
        WireCodec::key($originalKey);
        WireCodec::id($sequenceId);
        $values = WireCodec::object($wire, ['subject_type', 'subject_id', 'expected_definition_version']);
        $subject = WireCodec::subject($values->subject_type);
        $subjectId = WireCodec::id($values->subject_id);
        $version = WireCodec::version($values->expected_definition_version);

        return new self('enrol', $sequenceId, $version, $wire, $originalKey, $subject, $subjectId);
    }
}
