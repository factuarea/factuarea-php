<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Native139;

use Factuarea\Sdk\Custom\Crm\EntityExports\EntityType;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportIntent;
use Factuarea\Sdk\Custom\Crm\Native139\Model;
use Factuarea\Sdk\Custom\Crm\Native139\SourceClient;
use Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports\ApplyOriginalIntent;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\UnconfirmedMutationException;
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
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/** Prepared HTTP boundary cases; no server, provider, account, installation or release is exercised. */
final class NativeContract139HttpTest extends TestCase
{
    private const RUN = '019d75c3-7139-7000-8000-000000000139';

    /** @var list<array<string,mixed>> */
    private array $history = [];

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses, \stdClass $credentials): SourceClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()->setServerUrl('https://native-source139.invalid/v1')
            ->setSecuritySource(static fn (): Security => new Security(bearerAuth: $credentials->token))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone())->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());

        return SourceClient::privateContract($sdk);
    }

    /** @param array<string,mixed> $body */
    private function json(array $body, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($body, JSON_THROW_ON_ERROR));
    }

    public function test_native_original_recovery_retains_bytes_current_authority_and_never_replays_creates(): void
    {
        $bytes = '{"confirmed":true,"effect_uuid":"019d75c3-7139-7000-8000-000000000140","expected_version":1,"mode":"import","preview_hash":"'.str_repeat('a', 64).'"}';
        $intent = ApplyOriginalIntent::restore(self::RUN, $bytes, hash('sha256', $bytes), 'retained-native-import139');
        $original = ['id' => self::RUN, 'source_blob_id' => '019d75c3-7139-7000-8000-000000000141', 'entity_type' => 'person',
            'version' => 2, 'state' => 'applying', 'preview_hash' => str_repeat('a', 64), 'counts' => (object) ['total' => 1, 'applied' => 0],
            'blocking_codes' => [], 'progress' => ['total' => 1, 'processed' => 0, 'pending' => 1]];
        $current = [...$original, 'version' => 3, 'state' => 'completed', 'counts' => (object) ['total' => 1, 'applied' => 1],
            'progress' => ['total' => 1, 'processed' => 1, 'pending' => 0],
            'errors' => ['version' => 3, 'items' => [], 'has_more' => false, 'next_row' => null, 'next_cursor' => null]];
        $originalBytes = json_encode(['data' => $original], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $responses = [];
        foreach (['missing', 'in_progress', 'unconfirmed'] as $state) {
            $responses[] = $this->json(['data' => ['original_body_sha256' => $intent->originalBodySha256,
                'authorization_version' => 'current-native-authority139', 'state' => $state, 'original_response' => null]]);
        }
        $responses[] = $this->json(['data' => $current]);
        $responses[] = $this->json(['data' => ['original_body_sha256' => $intent->originalBodySha256,
            'authorization_version' => 'reauthorized-native139', 'state' => 'confirmed',
            'original_response' => ['status' => 202, 'body' => $originalBytes]]]);
        $opportunityBody = new Model\CrmCreateOpportunityRequest('Native opportunity', self::RUN,
            '019d75c3-7139-7000-8000-000000000150', '9007199254740993.0100', 'EUR', ownerId: null);
        $activityBody = new Model\CrmCreateCrmActivityRequest('note', 'Native note',
            new Model\CrmCreateCrmActivityRequestTarget('opportunity', self::RUN), 'Europe/Madrid', body: null);
        self::assertArrayNotHasKey('lead_id', $opportunityBody->toArray());
        self::assertArrayHasKey('owner_id', $opportunityBody->toArray());
        self::assertNull($opportunityBody->ownerId);
        self::assertSame('9007199254740993.0100', $opportunityBody->amount);
        $opportunitySnapshot = ['id' => self::RUN, 'version' => 1, 'status' => 'open', 'title' => 'Native opportunity',
            'override_reason' => null, 'next_step' => null, 'close_reason' => null, 'lead_id' => null, 'contact_id' => null,
            'pipeline_id' => self::RUN, 'stage_id' => '019d75c3-7139-7000-8000-000000000150', 'team_id' => null,
            'loss_reason_id' => null, 'owner_id' => null, 'amount' => '9007199254740993.0100', 'weighted_value' => null,
            'probability' => null, 'probability_override' => null, 'currency' => 'EUR', 'expected_close' => null,
            'closed_at' => null, 'stage_changed_at' => null, 'participants' => [], 'playbook_answers' => [], 'history' => []];
        $opportunityOriginal = json_encode(['data' => $opportunitySnapshot], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $activityOriginal = json_encode(['data' => ['state' => 'confirmed', 'effect_id' => self::RUN,
            'activity_id' => '019d75c3-7139-7000-8000-000000000151', 'activity' => null]], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        foreach ([['opportunity', $opportunityBody, $opportunityOriginal], ['crmactivity', $activityBody, $activityOriginal]] as [$path, $body, $reply]) {
            $responses[] = new ConnectException('Lost native creation reply', new Request('POST', 'https://native-source139.invalid/v1/crm/'.$path));
            $responses[] = $this->json(['data' => ['state' => 'unconfirmed', 'original_body_sha256' => hash('sha256', $body->toJson()),
                'original_response' => null], 'meta' => ['authorization_version' => 'current-create139']]);
            $responses[] = $this->json(['data' => ['state' => 'confirmed', 'original_body_sha256' => hash('sha256', $body->toJson()),
                'original_response' => ['status' => 201, 'body' => $reply]], 'meta' => ['authorization_version' => 'current-confirmed139']]);
        }
        $responses[] = $this->json(['data' => [...$opportunitySnapshot, 'version' => 9, 'title' => 'Current edited title']]);
        $credentials = (object) ['token' => 'fact_test_native_before139'];
        $client = $this->client($responses, $credentials);
        foreach (['missing', 'in_progress', 'unconfirmed'] as $state) {
            $credentials->token = 'fact_test_native_'.$state.'139';
            $result = $client->imports->original($intent);
            self::assertSame($state, $result->body->data->state);
            self::assertNull($result->body->data->originalResponse);
            self::assertSame('Bearer '.$credentials->token, $this->history[array_key_last($this->history)]['request']->getHeaderLine('Authorization'));
        }
        $currentResult = $client->imports->current(new Model\PublicApiV1CrmSpreadsheetImportsShowParameters(self::RUN));
        self::assertSame(3, $currentResult->body->toArray()['data']->version);
        $credentials->token = 'fact_test_native_current139';
        $confirmed = $client->imports->original($intent);
        self::assertSame('confirmed', $confirmed->body->data->state);
        self::assertSame(202, $confirmed->body->data->originalResponse->status);
        self::assertSame($originalBytes, $confirmed->body->data->originalResponse->body, 'An Original response is exact bytes, not a serialization of Current.');
        self::assertSame($bytes, $intent->originalBody);
        foreach ($this->history as $index => $entry) {
            $request = $entry['request'];
            self::assertSame('GET', $request->getMethod());
            self::assertSame('', (string) $request->getBody());
            if ($index !== 3) {
                parse_str($request->getUri()->getQuery(), $query);
                self::assertSame(['original_body_sha256' => hash('sha256', $bytes)], $query);
                self::assertSame('retained-native-import139', $request->getHeaderLine('Idempotency-Key'));
                self::assertSame('/v1/crm/imports/'.self::RUN.'/apply/original', $request->getUri()->getPath());
            }
        }
        self::assertSame('Bearer fact_test_native_current139', $this->history[4]['request']->getHeaderLine('Authorization'));
        try {
            ApplyOriginalIntent::restore(self::RUN, $bytes.' ', $intent->originalBodySha256, $intent->idempotencyKey);
            self::fail('Replacing retained bytes must be rejected before any recovery request.');
        } catch (\InvalidArgumentException) {
            self::assertCount(5, $this->history);
        }
        foreach ([$opportunityBody, $activityBody] as $index => $body) {
            $key = 'native-create139-'.$index;
            $wire = $body->toJson();
            $offset = count($this->history);
            try {
                if ($index === 0) {
                    $client->opportunities->crmCreateOpportunity(new Model\CrmCreateOpportunityParameters($body, $key));
                } else {
                    $client->activities->crmCreateCrmActivity(new Model\CrmCreateCrmActivityParameters($body, $key));
                }
                self::fail('A lost creation reply cannot establish a successful write.');
            } catch (UnconfirmedMutationException $failure) {
                self::assertSame('unconfirmed', $failure->outcome);
                self::assertSame($key, $failure->idempotencyKey);
            }
            $credentials->token = 'fact_test_native_create_current139-'.$index;
            for ($read = 0; $read < 2; $read++) {
                $original = $index === 0
                    ? $client->opportunities->crmGetOpportunityCreateOriginal(new Model\CrmGetOpportunityCreateOriginalParameters(hash('sha256', $wire), $key))
                    : $client->activities->crmGetCrmActivityCreateOriginal(new Model\CrmGetCrmActivityCreateOriginalParameters(hash('sha256', $wire), $key));
                self::assertSame($read === 0 ? 'unconfirmed' : 'confirmed', $original->body->data->state);
                if ($read === 0) {
                    self::assertNull($original->body->data->originalResponse);
                } else {
                    self::assertSame(201, $original->body->data->originalResponse->status);
                    self::assertSame($index === 0 ? $opportunityOriginal : $activityOriginal, $original->body->data->originalResponse->body);
                }
            }
            $requests = array_slice($this->history, $offset, 3);
            self::assertSame(['POST', 'GET', 'GET'], array_map(static fn ($entry): string => $entry['request']->getMethod(), $requests));
            self::assertSame($wire, (string) $requests[0]['request']->getBody());
            foreach (array_slice($requests, 1) as $entry) {
                $request = $entry['request'];
                self::assertSame('/v1/crm/'.($index === 0 ? 'opportunity' : 'crmactivity').'/create/original', $request->getUri()->getPath());
                self::assertSame('', (string) $request->getBody());
                self::assertSame($key, $request->getHeaderLine('Idempotency-Key'));
                self::assertSame('Bearer '.$credentials->token, $request->getHeaderLine('Authorization'));
                parse_str($request->getUri()->getQuery(), $query);
                self::assertSame(['original_body_sha256' => hash('sha256', $wire)], $query);
            }
        }
        $currentOpportunity = $client->opportunities->crmGetOpportunity(new Model\CrmGetOpportunityParameters(self::RUN));
        self::assertSame(9, $currentOpportunity->body->data->version);
        self::assertSame('9007199254740993.0100', $currentOpportunity->body->data->amount);
        self::assertSame('Native opportunity', json_decode($opportunityOriginal, true, flags: JSON_THROW_ON_ERROR)['data']['title']);
    }

    public function test_six_owner_exports_reuse_original_and_stream_exact_money_while_document_masks_and_agenda_served_layers_stay_current(): void
    {
        $responses = [];
        $intents = [];
        $csv = [];
        foreach (EntityType::cases() as $index => $type) {
            $id = '019d75c3-7139-7000-8000-'.sprintf('%012d', 200 + $index);
            $columns = match ($type) {
                EntityType::Opportunity => ['uuid', 'amount', 'currency'], EntityType::ServiceTicket => ['uuid'], default => ['id'],
            };
            $intents[] = new ExportIntent($id, $type, [], $columns, true, 'source139-export-'.$type->value);
            $csv[] = $type === EntityType::Opportunity ? "uuid;amount;currency\n$id;9007199254740993.0100;EUR\n" : $columns[0]."\n$id\n";
            $ready = ['id' => $id, 'entity_type' => $type->value, 'status' => 'ready', 'filename' => 'crm-'.$type->value.'.csv', 'size_bytes' => strlen($csv[$index])];
            $responses[] = $this->json(['data' => ['id' => $id, 'entity_type' => $type->value, 'status' => 'processing']], 202);
            $responses[] = $this->json(['data' => $ready]);
            $responses[] = $this->json(['data' => $ready]);
            $responses[] = new Response(200, ['Content-Type' => 'text/csv'], $csv[$index]);
        }
        $responses[] = $this->json(['data' => ['id' => self::RUN, 'version' => 3]]);
        $responses[] = $this->json(['data' => [], 'has_more' => false, 'next_cursor' => null, 'sources' => ['tasks']]);
        $responses[] = $this->json(['error' => ['type' => 'authorization_error', 'code' => 'insufficient_scope',
            'message' => 'The current native document grant was revoked.', 'doc_url' => 'https://native-source139.invalid/errors', 'request_id' => 'req_native139']], 403);
        $credentials = (object) ['token' => 'fact_test_native_export139'];
        $client = $this->client($responses, $credentials);
        foreach ($intents as $index => $intent) {
            $pending = $client->entityExports->request($intent);
            self::assertSame('processing', $pending->body->data->status);
            self::assertArrayNotHasKey('filename', $pending->body->data->toArray());
            $ready = $client->entityExports->request($intent);
            self::assertSame('ready', $ready->body->data->status, 'A known intent uses its existing native Original GET.');
            $download = $client->entityExports->download($intent);
            self::assertSame($csv[$index], $download->stream()->getContents());
            $requests = array_slice($this->history, $index * 4, 4);
            self::assertSame(['POST', 'GET', 'GET', 'GET'], array_map(static fn ($entry): string => $entry['request']->getMethod(), $requests));
            self::assertSame($intent->originalBody, (string) $requests[0]['request']->getBody());
            self::assertSame($intent->entityType->value, json_decode((string) $requests[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR)['entity_type']);
        }
        $document = $client->documents->crmGetCommercialDocument(new Model\CrmGetCommercialDocumentParameters(self::RUN))->body->data;
        self::assertSame(Omitted::Value, $document->title, 'A masked field is omitted, never populated from an older response.');
        self::assertSame(3, $document->version);
        $agenda = $client->agenda->publicApiV1AgendaList(new Model\PublicApiV1AgendaListParameters('2026-10-01', '2026-10-31', sources: 'tasks,fiscal_deadline'));
        self::assertSame(['tasks'], $agenda->body->sources, 'Only the layers served by the owner are factual; no scope or free/busy is inferred.');
        self::assertFalse($agenda->body->hasMore);
        self::assertNull($agenda->body->nextCursor);
        $credentials->token = 'fact_test_native_revoked139';
        try {
            $client->documents->crmGetCommercialDocument(new Model\CrmGetCommercialDocumentParameters(self::RUN));
            self::fail('The current native error must propagate.');
        } catch (ErrorThrowable $denied) {
            self::assertSame(403, $denied->container->rawResponse->getStatusCode());
            self::assertSame('insufficient_scope', $denied->container->error->code);
        }
        self::assertSame('Bearer fact_test_native_revoked139', $this->history[array_key_last($this->history)]['request']->getHeaderLine('Authorization'));
    }
}
