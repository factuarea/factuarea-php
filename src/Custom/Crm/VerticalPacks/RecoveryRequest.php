<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;
/** Read-only exact original tuple, not another invocation, head lookup or receipt mint. */
final class RecoveryRequest extends WireModel
{
    public const SCHEMA = Schemas::RECOVERY_REQUEST;
    public const FIELDS = ['operation' => ['property' => 'operation', 'type' => ['scalar' => 'string']],
        'target_id' => ['property' => 'targetId', 'type' => ['scalar' => 'string']],
        'original_key' => ['property' => 'originalKey', 'type' => ['scalar' => 'string']],
        'original_wire' => ['property' => 'originalWire', 'type' => ['scalar' => 'string']]];
    public function __construct(public readonly string $operation, public readonly string $targetId,
        #[\SensitiveParameter] public readonly string $originalKey, #[\SensitiveParameter] public readonly string $originalWire)
    {
        $this->validate();
        match ($operation) {
            'apply' => WireCodec::apply($originalWire), 'undo' => WireCodec::undo($originalWire),
            default => throw new \InvalidArgumentException('Only native apply/undo can be recovered.')
        };
    }
    public static function fromIntent(OriginalIntent $intent): self
    {
        return new self($intent->operation, $intent->targetId, $intent->originalKey, $intent->originalWire);
    }
}
