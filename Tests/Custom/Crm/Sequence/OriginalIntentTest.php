<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\Sequence\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\Sequence\WireCodec;
use PHPUnit\Framework\TestCase;

final class OriginalIntentTest extends TestCase
{
    public function test_original_bytes_and_absent_versus_empty_stop_conditions_survive_closed_validation(): void
    {
        $definition = '{"timezone":"Europe/Madrid","ambiguous_offset":"later","allowed_windows":[{"weekday":1,"start_minute":0,"end_minute":1440}],"steps":[{"type":"wait","wait_seconds":60,"recipe_id":null}],"stop_on_closure":true,"purpose":"crm_sequence","channel":"email"}';
        $wire = "{\n \"definition\": ".$definition.", \"expected_version\" : 0\n}";
        $key = str_repeat('opaque-original-', 22);
        $intent = OriginalIntent::save($wire, $key);
        self::assertSame($wire, $intent->originalWire);
        self::assertSame($key, $intent->originalKey);
        self::assertNull($intent->targetId);
        $parsedDefinition = WireCodec::decode($intent->originalWire)->definition;
        self::assertInstanceOf(\stdClass::class, $parsedDefinition);
        self::assertFalse(property_exists($parsedDefinition, 'stop_conditions'));
        $explicit = str_replace('"channel":"email"}', '"channel":"email","stop_conditions":[]}', $wire);
        $explicitDefinition = WireCodec::decode(OriginalIntent::save($explicit, $key)->originalWire)->definition;
        self::assertInstanceOf(\stdClass::class, $explicitDefinition);
        self::assertSame([], $explicitDefinition->stop_conditions);
        foreach ([
            str_replace('"expected_version" : 0', '"expected_version":0,"expected_\\u0076ersion":0', $wire),
            str_replace('"recipe_id":null', '"recipe_uuid":null', $wire),
            str_replace('"channel":"email"}', '"channel":"email","stop_conditions":null}', $wire),
            str_replace('"channel":"email"}', '"channel":"email","stop_conditions":["complaint","complaint"]}', $wire),
            str_replace('"expected_version" : 0', '"expected_version":0,"id":"019a4afd-5000-7000-8000-000000000001"', $wire),
        ] as $invalid) {
            try {
                OriginalIntent::save($invalid, $key);
                self::fail('An invalid or authority-bearing original intent was admitted.');
            } catch (\InvalidArgumentException $exception) {
                self::assertNotEmpty($exception->getMessage());
            }
        }
    }
}
