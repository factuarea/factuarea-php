<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\EntityExports;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\EntityExports\EntityType;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportIntent;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportStatus;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportUnconfirmedException;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/** Source110 causal fixtures only: the handler consumes every request without external I/O. */
final class EntityExportsHttp110Test extends TestCase
{
    /** @var list<array<string,mixed>> */
    private array $history = [];

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses, \stdClass $credentials): CrmClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()
            ->setServerUrl('https://export-fixture.invalid/v1')
            ->setSecuritySource(function () use ($credentials): Security {
                return new Security(bearerAuth: $credentials->token);
            })
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone())
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());

        return new CrmClient($sdk);
    }

    /** @param array<string,string|int> $job */
    private function response(array $job, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode(['data' => $job], JSON_THROW_ON_ERROR));
    }

    public function test_lost_reply_restores_exact_intent_and_reads_original_with_current_security_without_post_replay(): void
    {
        $id = '019d75c3-5100-7000-8000-000000000110';
        $credentials = (object) ['token' => 'fact_test_export_before'];
        $client = $this->client([
            new ConnectException('Fixture reply lost after request', new Request('POST', 'https://export-fixture.invalid/v1/crm/entity-exports')),
            $this->response(['id' => $id, 'entity_type' => 'organization', 'status' => 'ready', 'filename' => 'crm-organization.csv', 'size_bytes' => 51]),
        ], $credentials);
        $filters = ['search' => 'Málaga%', 'updated_from' => '2026-10-01T00:00:00Z'];
        $columns = ['updated_at', 'id', 'name'];
        $intent = new ExportIntent($id, EntityType::Organization, $filters, $columns, true, 'retained export110 lost reply');
        $filters['search'] = 'replacement';
        $columns[] = 'status';
        $intent->request()->filters->search = 'changed exposed DTO';

        try {
            $client->entityExports->request($intent);
            self::fail('A lost response cannot claim a confirmed native export result.');
        } catch (ExportUnconfirmedException $failure) {
            self::assertSame($intent, $failure->intent);
            self::assertSame('unconfirmed', $failure->outcome);
        }
        $credentials->token = 'fact_test_export_current';
        $restored = ExportIntent::restore($intent->originalBody, $intent->originalBodySha256, $intent->idempotencyKey);
        $result = $client->entityExports->request($restored);
        self::assertSame($id, $result->body->data->id);
        self::assertSame(ExportStatus::Ready, $result->body->data->state());
        self::assertSame($intent->originalBodySha256, $restored->originalBodySha256);
        self::assertCount(2, $this->history);
        $issued = $this->history[0]['request'];
        $read = $this->history[1]['request'];
        self::assertSame('POST', $issued->getMethod());
        self::assertSame($intent->originalBody, (string) $issued->getBody());
        self::assertSame($intent->originalBodySha256, hash('sha256', (string) $issued->getBody()));
        self::assertSame(['updated_at', 'id', 'name'], json_decode((string) $issued->getBody(), true)['columns']);
        self::assertSame('GET', $read->getMethod());
        self::assertSame('/v1/crm/entity-exports/'.$id, $read->getUri()->getPath());
        self::assertSame('', $read->getUri()->getQuery());
        self::assertSame('', (string) $read->getBody());
        self::assertSame('Bearer fact_test_export_current', $read->getHeaderLine('Authorization'));
        foreach ($this->history as $entry) {
            self::assertSame($intent->idempotencyKey, $entry['request']->getHeaderLine('Idempotency-Key'));
            self::assertFalse($entry['options']['allow_redirects']);
        }
    }

    public function test_six_native_owners_keep_omitted_ready_fields_and_stream_exact_decimals_then_propagate_current_denial(): void
    {
        $responses = [];
        $intents = [];
        $csv = "uuid,amount,currency,weighted_value\r\n019d75c3-5100-7000-8000-000000000120,9007199254740993.0100,EUR,4503599627370496.5050\r\n";
        foreach (EntityType::cases() as $index => $type) {
            $id = sprintf('019d75c3-5200-7000-8000-%012d', $index + 120);
            $columns = match ($type) {
                EntityType::Opportunity => ['uuid', 'amount', 'currency', 'weighted_value'],
                EntityType::ServiceTicket => ['uuid'],
                default => ['id'],
            };
            $filters = $type === EntityType::Activity ? ['type' => 'note', 'due_from' => '2026-10-01T00:00:00Z', 'due_to' => '2026-10-11T00:00:00Z'] : [];
            $intent = new ExportIntent($id, $type, $filters, $columns, true, 'retained export110 '.$type->value);
            $intents[] = $intent;
            $responses[] = $this->response(['id' => $id, 'entity_type' => $type->value, 'status' => 'processing'], 202);
        }
        $opportunity = $intents[3];
        $ready = ['id' => $opportunity->id, 'entity_type' => 'opportunity', 'status' => 'ready', 'filename' => 'crm-opportunity.csv', 'size_bytes' => strlen($csv)];
        $responses[] = $this->response($ready);
        $responses[] = new Response(200, ['Content-Type' => 'text/csv; charset=utf-8', 'Content-Disposition' => 'attachment; filename="crm-opportunity.csv"'], $csv);
        $responses[] = $this->response($ready);
        $responses[] = new Response(403, ['Content-Type' => 'application/json'], json_encode(['error' => ['type' => 'authorization_error', 'code' => 'crm_context_revoked', 'message' => 'Fixture current native authority revoked']], JSON_THROW_ON_ERROR));
        $credentials = (object) ['token' => 'fact_test_export_six'];
        $client = $this->client($responses, $credentials);
        foreach ($intents as $index => $intent) {
            $processing = $client->entityExports->request($intent);
            self::assertSame(ExportStatus::Processing, $processing->body->data->state());
            self::assertSame(Omitted::Value, $processing->body->data->filename);
            self::assertSame(Omitted::Value, $processing->body->data->sizeBytes);
            $wire = json_decode((string) $this->history[$index]['request']->getBody(), true);
            self::assertSame($intent->entityType->value, $wire['entity_type']);
            self::assertSame($intent->request()->columns, $wire['columns']);
            self::assertSame($intent->originalBodySha256, hash('sha256', (string) $this->history[$index]['request']->getBody()));
        }
        $download = $client->entityExports->download($opportunity);
        self::assertSame($csv, $download->stream()->getContents());
        self::assertSame(strlen($csv), $download->job->sizeBytes);
        self::assertTrue($this->history[7]['options']['stream']);
        self::assertSame('text/csv', $this->history[7]['request']->getHeaderLine('Accept'));
        $credentials->token = 'fact_test_export_changed';
        try {
            $client->entityExports->download($opportunity);
            self::fail('A previous ready result cannot suppress a current native download denial.');
        } catch (ErrorThrowable $failure) {
            self::assertSame('crm_context_revoked', $failure->container->error->code);
            self::assertSame(403, $failure->container->rawResponse?->getStatusCode());
        }
        self::assertCount(10, $this->history);
        foreach (array_slice($this->history, 6) as $entry) {
            self::assertSame('GET', $entry['request']->getMethod());
            self::assertSame('', $entry['request']->getUri()->getQuery());
            self::assertSame('', (string) $entry['request']->getBody());
            self::assertSame($opportunity->idempotencyKey, $entry['request']->getHeaderLine('Idempotency-Key'));
        }
        self::assertSame('Bearer fact_test_export_changed', $this->history[9]['request']->getHeaderLine('Authorization'));
    }
}
