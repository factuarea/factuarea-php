<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Request;

use Factuarea\Sdk\Models\Components\UpdateProjectV1Request;
use Factuarea\Sdk\Models\Components\UpdateSeriesRequest;
use Factuarea\Sdk\Utils\JSON;
use PHPUnit\Framework\TestCase;

/**
 * Spike F6: what travels on the wire for an optional + nullable field of a
 * generated request model. Measured against the pinned 0.6.0 generation.
 *
 * Result: every optional property carries `SkipWhenNull`, so "not assigned" and
 * "assigned null" are indistinguishable (both omitted) and `null` can never be
 * sent through the model. The API contract (omit keeps, `null` clears) needs
 * {@see \Factuarea\Sdk\Custom\Request\RequestOverrides} until the generator
 * emits a tri-state.
 */
final class PresenceWireTest extends TestCase
{
    private function wire(object $model): string
    {
        return JSON::createSerializer()->serialize($model, 'json');
    }

    public function test_unassigned_field_is_omitted(): void
    {
        $this->assertSame('{}', $this->wire(new UpdateSeriesRequest()));
        $this->assertSame('{}', $this->wire(new UpdateProjectV1Request()));
    }

    public function test_field_assigned_null_is_also_omitted(): void
    {
        // `description` is `type: [string, null]` in the spec ("null clears it").
        $this->assertSame('{}', $this->wire(new UpdateProjectV1Request(description: null)));

        $series = new UpdateSeriesRequest(name: 'B2B');
        $series->name = null;
        $this->assertSame('{}', $this->wire($series));
    }

    public function test_field_with_value_is_sent(): void
    {
        $this->assertSame('{"name":"B2B"}', $this->wire(new UpdateSeriesRequest(name: 'B2B')));
        $this->assertSame('{"description":"x"}', $this->wire(new UpdateProjectV1Request(description: 'x')));
    }

    public function test_generated_models_send_an_explicit_null(): void
    {
        $this->markTestIncomplete(
            'Pending regeneration [S]: the generated models cannot send an explicit null for an optional nullable '
            .'field (SkipWhenNull on every optional property). Until the generator emits a tri-state, use '
            .'RequestOverrides::run(clear: [...]). When it does, assert {"description":null} for '
            .'new UpdateProjectV1Request(description: null) and drop the overlay-free workaround.'
        );
    }
}
