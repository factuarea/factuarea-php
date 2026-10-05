<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Idempotency;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class IdempotencyClientTest extends TestCase
{
    public function test_explicit_option_header_wins_case_insensitively(): void
    {
        $history = [];
        $client = self::client($history, 1);

        $client->send(new Request('POST', 'https://example.test/purchase_scans'), [
            'headers' => ['idempotency-key' => 'caller-chosen-key'],
        ]);

        self::assertSame('caller-chosen-key', $history[0]['request']->getHeaderLine('Idempotency-Key'));
    }

    public function test_automatic_key_is_stable_when_retry_resends_the_same_request(): void
    {
        $history = [];
        $client = self::client($history, 2);
        $request = new Request('POST', 'https://example.test/purchase_scans');

        $client->send($request);
        $client->send($request);

        $first = $history[0]['request']->getHeaderLine('Idempotency-Key');
        self::assertNotSame('', $first);
        self::assertSame($first, $history[1]['request']->getHeaderLine('Idempotency-Key'));
    }

    public function test_get_request_has_no_automatic_key(): void
    {
        $history = [];
        $client = self::client($history, 1);

        $client->send(new Request('GET', 'https://example.test/purchase_scans'));

        self::assertFalse($history[0]['request']->hasHeader('Idempotency-Key'));
    }

    /**
     * @param  list<array{request: RequestInterface}>  $history
     */
    private static function client(array &$history, int $responses): IdempotencyClient
    {
        $mock = new MockHandler(array_fill(0, $responses, new Response(200)));
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        return new IdempotencyClient(new Client(['handler' => $stack]));
    }
}
