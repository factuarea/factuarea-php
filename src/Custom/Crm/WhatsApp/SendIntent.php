<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\WhatsApp;
use InvalidArgumentException;
final readonly class SendIntent
{
    public string $firstWire;
    public string $originalWireHash;
    public function __construct(public Preparation $preparation, ?string $firstWire = null)
    {
        $p = $preparation->data;
        $expected = array_intersect_key($p, array_flip(['conversation_id', 'effect_id', 'expected_version', 'message_version', 'purpose', 'template', 'preparation_token']));
        $this->firstWire = $firstWire ?? json_encode($expected, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!Preparation::text($this->firstWire, 65536) || !json_decode($this->firstWire, false, 32, JSON_THROW_ON_ERROR) instanceof \stdClass) {
            throw new InvalidArgumentException('Invalid native WhatsApp wire.');
        }
        $data = json_decode($this->firstWire, true, 32, JSON_THROW_ON_ERROR);
        Preparation::keys($data, array_keys($expected));
        $data['template'] = Preparation::template($data['template']);
        foreach ($expected as $key => $value) {
            if ($data[$key] !== $value) {
                throw new InvalidArgumentException('The first wire must match its sealed preparation.');
            }
        }
        $this->originalWireHash = hash('sha256', $this->firstWire);
    }
}
