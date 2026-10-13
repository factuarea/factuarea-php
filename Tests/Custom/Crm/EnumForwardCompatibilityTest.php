<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use Factuarea\Sdk\Custom\Crm\Model\CrmCreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\Model\LeadNextAction;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class EnumForwardCompatibilityTest extends TestCase
{
    public function test_unknown_response_enum_is_retained_while_request_serialization_stays_closed(): void
    {
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'status' => 'scheduled_for_a_future_state',
                'activity_id' => null,
                'type' => null,
                'title' => null,
                'due_at' => null,
                'reason' => null,
            ], JSON_THROW_ON_ERROR)),
        ]));
        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_enum_compatibility'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone())
            ->build();

        $response = (new Transport($sdk->sdkConfiguration))->request(
            'GET',
            'crm/enum-fixture',
            'crm.enum_fixture',
            LeadNextAction::class,
        );

        self::assertSame('scheduled_for_a_future_state', $response->body->status);

        $request = new CrmCreateLeadRequest(name: 'SDK fixture', source: 'manual');
        $request->source = 'a_future_source';
        $this->expectException(\InvalidArgumentException::class);
        $request->toJson();
    }
}
