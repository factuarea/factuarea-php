<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Native139;

use Factuarea\Sdk\Custom\Crm\Native139\Documents\RestoreIntent;
use Factuarea\Sdk\Custom\Crm\Native139\Documents\RestoreUnconfirmedException;
use Factuarea\Sdk\Custom\Crm\Native139\Model;
use Factuarea\Sdk\Custom\Crm\Native139\SourceClient;
use Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports\ApplyIntent;
use Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports\ApplyUnconfirmedException;
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

/** Prepared against the literal native143 export; no API, database, account or test execution is performed. */
final class NativeContract143HttpTest extends TestCase
{
    private const RUN = '019d75c3-7143-7000-8000-000000000143';
    private const CHILD = '019d75c3-7143-7000-8000-000000000144';
    private const CORRECTED = '019d75c3-7143-7000-8000-000000000145';

    /** @var list<array<string,mixed>> */
    private array $history = [];

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses, \stdClass $credentials): SourceClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()->setServerUrl('https://native-source143.invalid/v1')
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

    /** @return array<string,mixed> */
    private function run(string $id, ?string $parent = null): array
    {
        $run = ['id' => $id, 'source_blob_id' => self::CORRECTED, 'entity_type' => 'person', 'version' => 2,
            'state' => 'applying', 'preview_hash' => str_repeat('a', 64), 'counts' => (object) ['total' => 1, 'applied' => 0],
            'blocking_codes' => [], 'progress' => ['total' => 1, 'processed' => 0, 'pending' => 1]];
        if ($parent !== null) {
            $run['parent_run_id'] = $parent;
        }

        return $run;
    }

    /** @return array<string,mixed> */
    private function original(ApplyIntent $intent, string $state, ?string $bytes = null): array
    {
        return ['data' => ['original_body_sha256' => $intent->originalBodySha256,
            'authorization_version' => 'current-authority143-'.$state, 'state' => $state,
            'original_response' => $bytes === null ? null : ['status' => 202, 'body' => $bytes]]];
    }

    public function test_apply_uses_native_202_and_reviewed_bytes_then_only_reads_original_with_current_authority(): void
    {
        $body = new Model\PublicApiV1CrmSpreadsheetImportsApplyParametersBody(1, str_repeat('a', 64),
            strtoupper('019d75c3-7143-7000-8000-000000000146'), 'retry_failed', true, self::CORRECTED);
        $intent = ApplyIntent::reviewed(strtoupper(self::RUN), $body, 'source143 retained apply');
        $body->expectedVersion = 99;
        $body->correctedSourceBlobId = self::RUN;
        $run = $this->run(self::CHILD, self::RUN);
        $originalBytes = json_encode(['data' => $run], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $current = [...$run, 'version' => 3, 'state' => 'completed', 'counts' => (object) ['total' => 1, 'failed' => 1],
            'progress' => ['total' => 1, 'processed' => 1, 'pending' => 0],
            'errors' => ['version' => 3, 'items' => [['source_row' => 2, 'field' => 'amount', 'codes' => ['invalid_decimal']]],
                'has_more' => false, 'next_row' => null, 'next_cursor' => null]];
        $timeout = ApplyIntent::reviewed('019d75c3-7143-7000-8000-000000000147',
            new Model\PublicApiV1CrmSpreadsheetImportsApplyParametersBody(1, str_repeat('b', 64),
                '019d75c3-7143-7000-8000-000000000148', 'import', true), 'source143-timeout');
        $responses = [$this->json(['data' => $run], 202), $this->json($this->original($intent, 'unconfirmed')),
            $this->json(['data' => $current]), $this->json($this->original($intent, 'confirmed', $originalBytes)),
            $this->json($this->original($intent, 'confirmed', $originalBytes)),
            new ConnectException('Lost native Apply reply', new Request('POST', 'https://native-source143.invalid/v1/crm/imports/'.$timeout->run.'/apply')),
            $this->json($this->original($timeout, 'missing')),
            $this->json(['error' => ['type' => 'authorization_error', 'code' => 'insufficient_scope', 'message' => 'Current native authority was revoked.',
                'doc_url' => 'https://native-source143.invalid/errors', 'request_id' => 'req_native143']], 403)];
        $credentials = (object) ['token' => 'fact_test_source143_first'];
        $client = $this->client($responses, $credentials);
        $accepted = $client->imports->apply($intent);
        self::assertSame(202, $accepted->statusCode());
        self::assertSame(self::CHILD, $accepted->body->data->id);
        self::assertSame(self::RUN, $accepted->body->data->parentRunId);
        self::assertSame(1, $accepted->body->data->progress->pending, 'A native202 does not claim all rows completed.');
        self::assertSame($intent->originalBody, (string) $this->history[0]['request']->getBody());
        $posted = json_decode($intent->originalBody, true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(1, $posted['expected_version']);
        self::assertSame(self::CORRECTED, $posted['corrected_source_blob_id']);
        self::assertTrue($posted['confirmed']);
        self::assertFalse($this->history[0]['options']['allow_redirects']);
        $credentials->token = 'fact_test_source143_current';
        $pending = $client->imports->apply($intent);
        self::assertSame('unconfirmed', $pending->body->data->state);
        self::assertNull($pending->body->data->originalResponse);
        $currentResult = $client->imports->current(new Model\PublicApiV1CrmSpreadsheetImportsShowParameters(self::CHILD, limit: 25));
        self::assertSame(3, $currentResult->body->data->version);
        self::assertSame('amount', $currentResult->body->data->errors->items[0]->field);
        self::assertSame(['invalid_decimal'], $currentResult->body->data->errors->items[0]->codes);
        $confirmed = $client->imports->apply($intent);
        self::assertSame(202, $confirmed->body->data->originalResponse->status);
        self::assertSame($originalBytes, $confirmed->body->data->originalResponse->body);
        $restored = ApplyIntent::restore($intent->run, $intent->originalBody, $intent->originalBodySha256, $intent->idempotencyKey);
        self::assertSame($originalBytes, $client->imports->apply($restored)->body->data->originalResponse->body);
        self::assertSame(['POST', 'GET', 'GET', 'GET', 'GET'], array_map(static fn ($entry): string => $entry['request']->getMethod(), $this->history));
        foreach ([1, 3, 4] as $index) {
            $request = $this->history[$index]['request'];
            self::assertSame('/v1/crm/imports/'.$intent->run.'/apply/original', $request->getUri()->getPath());
            self::assertSame($intent->idempotencyKey, $request->getHeaderLine('Idempotency-Key'));
            self::assertSame('Bearer '.$credentials->token, $request->getHeaderLine('Authorization'));
            parse_str($request->getUri()->getQuery(), $query);
            self::assertSame(['original_body_sha256' => $intent->originalBodySha256], $query);
            self::assertSame('', (string) $request->getBody());
        }
        try {
            $client->imports->apply($timeout);
            self::fail('An ambiguous Apply cannot be reported as confirmed.');
        } catch (ApplyUnconfirmedException $uncertain) {
            self::assertSame($timeout, $uncertain->intent);
            self::assertSame('unconfirmed', $uncertain->outcome);
        }
        self::assertSame('missing', $client->imports->apply($timeout)->body->data->state);
        $credentials->token = 'fact_test_source143_revoked';
        try {
            $client->imports->original($timeout->original());
            self::fail('Original must propagate a current native denial.');
        } catch (ErrorThrowable $denied) {
            self::assertSame(403, $denied->container->rawResponse->getStatusCode());
        }
        self::assertSame('Bearer '.$credentials->token, $this->history[7]['request']->getHeaderLine('Authorization'));
        self::assertSame(['POST', 'GET', 'GET'], array_map(static fn ($entry): string => $entry['request']->getMethod(), array_slice($this->history, 5)));
        try {
            new Model\PublicApiV1CrmSpreadsheetImportsApplyParametersBody(1, str_repeat('a', 64), self::RUN, 'import', true, self::CORRECTED);
            self::fail('The actual native allOf forbids corrected blobs outside retry_failed.');
        } catch (\InvalidArgumentException) {
            self::assertCount(8, $this->history);
        }
        try {
            $client->imports->current(new Model\PublicApiV1CrmSpreadsheetImportsShowParameters(self::CHILD, cursor: 'opaque143', reportFormat: 'csv', expectedVersion: 3));
            self::fail('The native Query schema forbids mixing JSON cursors with CSV mode.');
        } catch (\InvalidArgumentException) {
            self::assertCount(8, $this->history);
        }
    }

    public function test_restore_returns_native_receipt_but_uncertain_original_remains_unknown_after_current_and_never_resends(): void
    {
        $approved = RestoreIntent::reviewed(self::RUN, new Model\CrmRestoreCommercialDocumentParametersBody(7, true), 'source143-restore-approved');
        $document = '019d75c3-7143-7000-8000-000000000149';
        $uncertain = RestoreIntent::reviewed($document, new Model\CrmRestoreCommercialDocumentParametersBody(2, true), 'source143-restore-uncertain');
        $redirect = RestoreIntent::reviewed('019d75c3-7143-7000-8000-000000000150',
            new Model\CrmRestoreCommercialDocumentParametersBody(1, true), 'source143-restore-redirect');
        $credentials = (object) ['token' => 'fact_test_source143_restore'];
        $client = $this->client([$this->json(['data' => ['id' => self::RUN, 'version' => 8]]),
            new ConnectException('Lost native restore reply', new Request('POST', 'https://native-source143.invalid/v1/crm/documents/'.$document.'/restore')),
            $this->json(['data' => ['id' => $document, 'version' => 3, 'archived_at' => null]]),
            new Response(302, ['Location' => 'https://native-source143.invalid/another-effect'])], $credentials);
        $receipt = $client->documentRestores->restore($approved);
        self::assertSame(200, $receipt->statusCode());
        self::assertSame(self::RUN, $receipt->body->data->id);
        self::assertSame(8, $receipt->body->data->version);
        $failure = null;
        try {
            $client->documentRestores->restore($uncertain);
            self::fail('A lost restore reply must remain unconfirmed.');
        } catch (RestoreUnconfirmedException $unknown) {
            $failure = $unknown;
            self::assertSame('unconfirmed', $unknown->outcome);
            self::assertSame('unknown', $unknown->originalState);
            self::assertFalse($unknown->canReadOriginal);
            self::assertSame($uncertain, $unknown->intent);
        }
        $credentials->token = 'fact_test_source143_restore_current';
        $current = $client->documents->crmGetCommercialDocument(new Model\CrmGetCommercialDocumentParameters($document));
        self::assertSame(3, $current->body->data->version);
        self::assertSame(Omitted::Value, $current->body->data->title);
        self::assertSame('unknown', $failure->originalState, 'Current cannot manufacture a restore Original confirmation.');
        $restored = RestoreIntent::restore($document, $uncertain->originalBody, $uncertain->originalBodySha256, $uncertain->idempotencyKey);
        foreach ([$uncertain, $restored] as $intent) {
            try {
                $client->documentRestores->restore($intent);
                self::fail('Neither a known nor retained restore may resend a POST without its missing Original carrier.');
            } catch (RestoreUnconfirmedException $unknown) {
                self::assertSame('unknown', $unknown->originalState);
                self::assertCount(3, $this->history);
            }
        }
        try {
            $client->documentRestores->restore($redirect);
            self::fail('A redirect does not establish the native200 restore receipt.');
        } catch (RestoreUnconfirmedException $unknown) {
            self::assertSame('unknown', $unknown->originalState);
            self::assertFalse($this->history[3]['options']['allow_redirects']);
            self::assertCount(4, $this->history);
        }
        self::assertSame(['POST', 'POST', 'GET', 'POST'], array_map(static fn ($entry): string => $entry['request']->getMethod(), $this->history));
        self::assertSame($uncertain->originalBody, (string) $this->history[1]['request']->getBody());
        self::assertSame($uncertain->idempotencyKey, $this->history[1]['request']->getHeaderLine('Idempotency-Key'));
        self::assertSame('/v1/crm/documents/'.$document.'/restore', $this->history[1]['request']->getUri()->getPath());
        self::assertSame('Bearer '.$credentials->token, $this->history[2]['request']->getHeaderLine('Authorization'));
        try {
            new Model\CrmRestoreCommercialDocumentParametersBody(2, false);
            self::fail('The native restore contract requires explicit confirmation.');
        } catch (\InvalidArgumentException) {
            self::assertCount(4, $this->history);
        }
    }
}
