<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

/** Keep this original tuple in application memory before dispatch; it contains no new entity or effect UUID. */
final readonly class OriginalIntent
{
    private function __construct(public string $operation, public string $targetId,
        #[\SensitiveParameter] public string $originalWire, #[\SensitiveParameter] public string $originalKey)
    {
    }

    public static function apply(Plan $plan, #[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key): self
    {
        $plan->validate();
        WireCodec::key($key);
        $body = WireCodec::apply($wire);
        if ($body->expectedPlanVersion !== $plan->version || !hash_equals($plan->previewHash, $body->previewHash)
            || $body->planToken !== $plan->planToken || $body->demoSelected !== $plan->demoSelected || $plan->blockingCodes !== []) {
            throw new \InvalidArgumentException('Apply must retain the same original plan/version/token/hash/demo selection.');
        }
        $selections = [];
        foreach ($body->resolutions as $resolution) {
            $selections[$resolution->itemId] = $resolution->resolution;
        }
        $conflicts = [];
        foreach ($plan->items as $item) {
            if ($item->decision === 'conflict') {
                $conflicts[] = $item->id;
                if (!in_array($selections[$item->id] ?? null, $item->allowedResolutions, true)) {
                    throw new \InvalidArgumentException('Choose exactly one native allowed resolution per conflict.');
                }
            }
        }
        if (count($selections) !== count($conflicts) || array_diff(array_keys($selections), $conflicts) !== []) {
            throw new \InvalidArgumentException('Do not add resolutions for a nonconflicting item.');
        }

        return new self('apply', $plan->id, $wire, $key);
    }

    public static function undo(Receipt $original, #[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key): self
    {
        $original->validate();
        WireCodec::key($key);
        $body = WireCodec::undo($wire);
        if ($original->operation !== 'apply' || $body->originalReceiptId !== $original->id
            || $body->expectedInstallationVersion !== $original->installation->version) {
            throw new \InvalidArgumentException('Undo requires the actual original applied receipt and installation CAS.');
        }

        return new self('undo', $original->installation->id, $wire, $key);
    }

    public static function retained(string $operation, string $targetId, #[\SensitiveParameter] string $wire, #[\SensitiveParameter] string $key): self
    {
        WireCodec::id($targetId);
        WireCodec::key($key);
        match ($operation) {
            'apply' => WireCodec::apply($wire), 'undo' => WireCodec::undo($wire),
            default => throw new \InvalidArgumentException('Only native apply/undo have an original intent.')
        };

        return new self($operation, $targetId, $wire, $key);
    }

    /** @return array<string,string> */
    public function __debugInfo(): array
    {
        return ['operation' => $this->operation, 'targetId' => $this->targetId, 'originalWire' => '[redacted]', 'originalKey' => '[redacted]'];
    }
}
