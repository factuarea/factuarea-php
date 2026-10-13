<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Documents;
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Crm\Documents\DocumentWriteUnconfirmed;
use Factuarea\Sdk\Custom\Crm\Documents\LibraryDocuments;
use Factuarea\Sdk\Custom\Crm\Documents\OpportunityDocuments;
use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\Documents\Request as R;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Errors;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** Native wire/transport contracts only: no network, database, producer certification or feature activation. */
final class DocumentContractTest extends TestCase
{
    private const DOCUMENT = '01900000-0000-7000-8000-000000000001';
    private const PARENT = '01900000-0000-7000-8000-000000000002';
    private const LINK = '01900000-0000-7000-8000-000000000003';
    private const REFERENCE = '01900000-0000-7000-8000-000000000004';
    private const OWNER = '550E8400-E29B-41D4-A716-446655440000';
    private const KEY = 'document-original-intent';
    /** @var list<array{request:RequestInterface}> */
    private array $history = [];
    /** @param list<Response|ConnectException> $responses */
    private function sdk(array $responses, string $key = 'fact_test_documents'): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        return Factuarea::builder()->setServerUrl('https://sdk.invalid/v1')->setClient(new Client(['handler' => $stack]))
            ->setSecurity(new Components\Security(bearerAuth: $key))->setRetryConfig(new RetryConfigBackoff(initialIntervalMs: 1, maxIntervalMs: 1, exponent: 1, maxElapsedTimeMs: 1000, retryConnectionErrors: true))->build();
    }
    /** @return array<string,array{string,string,int}> */
    public static function libraryWrites(): array
    {
        return ['upload' => ['uploadDocument', '/v1/crm/documents', 201], 'replace' => ['replaceVersion', '/v1/crm/documents/'.self::DOCUMENT.'/versions', 200],
            'share' => ['shareDocument', '/v1/crm/documents/'.self::DOCUMENT.'/shares', 200], 'revoke' => ['revokeShare', '/v1/crm/documents/'.self::DOCUMENT.'/shares/revoke', 200],
            'archive' => ['archiveDocument', '/v1/crm/documents/'.self::DOCUMENT.'/archive', 200]];
    }
    #[DataProvider('libraryWrites')]
    public function test_all_library_writes_use_existing_routes_closed_public_fields_and_exact_keys_once(string $method, string $path, int $status): void
    {
        $client = FactuareaClient::libraryDocuments($this->sdk([$this->json(['id' => self::DOCUMENT, 'version' => $status === 201 ? 1 : 7], $status)]));
        $result = match ($method) {
            'uploadDocument' => $client->uploadDocument(new R\UploadDocumentRequest('Privado', self::REFERENCE, 'application/pdf'), self::KEY),
            'replaceVersion' => $client->replaceVersion(strtoupper(self::DOCUMENT), new R\ReplaceDocumentVersionRequest(6, self::REFERENCE, 'application/pdf'), self::KEY),
            'shareDocument' => $client->shareDocument(self::DOCUMENT, new R\ShareDocumentRequest(6, 'user', self::OWNER, null), self::KEY),
            'revokeShare' => $client->revokeShare(self::DOCUMENT, new R\RevokeDocumentShareRequest(6, self::REFERENCE), self::KEY),
            'archiveDocument' => $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY),
            default => throw new LogicException('Unknown case.')
        };
        self::assertCount(1, $this->history);
        $request = $this->history[0]['request'];
        self::assertSame('POST', $request->getMethod());
        self::assertSame($path, $request->getUri()->getPath());
        self::assertSame(self::KEY, $request->getHeaderLine('Idempotency-Key'));
        self::assertSame('Bearer fact_test_documents', $request->getHeaderLine('Authorization'));
        self::assertSame(self::DOCUMENT, $result->data->id);
        self::assertSame($status, $result->statusCode());
        $wire = $this->body($request);
        foreach (['company_id', 'actor_id', 'sandbox', 'uuid', 'effect_id', 'confirmed', 'producer_reference_uuid', 'share_uuid'] as $field) {
            self::assertArrayNotHasKey($field, $wire);
        }
        if ($status !== 201) {
            self::assertSame(6, $wire['expected_version']);
        } else {
            self::assertSame(self::REFERENCE, $wire['producer_reference_id']);
        }
    }
    /** @return array<string,array{string,string,string}> */
    public static function opportunityWrites(): array
    {
        return ['quote' => ['createQuote', 'POST', '/v1/crm/opportunitydocuments/'.self::PARENT.'/quotes'],
            'link' => ['linkDocument', 'POST', '/v1/crm/opportunitydocuments/'.self::PARENT.'/documents'],
            'unlink' => ['unlinkDocument', 'DELETE', '/v1/crm/opportunitydocuments/'.self::PARENT.'/documents/'.self::LINK]];
    }
    #[DataProvider('opportunityWrites')]
    public function test_opportunity_operations_preserve_native_owner_uuid_and_link_cas_without_private_effect_fields(string $method, string $verb, string $path): void
    {
        $receipt = $method === 'unlinkDocument' ? ['id' => self::LINK, 'version' => 7, 'opportunity_id' => self::PARENT, 'document_type' => 'quote', 'document_id' => self::OWNER] : $this->link();
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($receipt, $method === 'unlinkDocument' ? 200 : 201)]));
        $result = match ($method) {
            'createQuote' => $client->createQuote(strtoupper(self::PARENT), new R\CreateOpportunityQuoteRequest(new R\QuoteDraft('2026-10-09', 'unassigned', null, notes: null), null), self::KEY),
            'linkDocument' => $client->linkDocument(self::PARENT, new R\LinkOpportunityDocumentRequest('quote', strtolower(self::OWNER), false), self::KEY),
            'unlinkDocument' => $client->unlinkDocument(self::PARENT, self::LINK, new R\CasRequest(6), self::KEY),default => throw new LogicException('Unknown case.')
        };
        self::assertCount(1, $this->history);
        $request = $this->history[0]['request'];
        self::assertSame($verb, $request->getMethod());
        self::assertSame($method === 'unlinkDocument' ? 200 : 201, $result->statusCode());
        self::assertSame($path, $request->getUri()->getPath());
        self::assertSame(self::KEY, $request->getHeaderLine('Idempotency-Key'));
        self::assertSame(self::OWNER, $result->data->documentId);
        self::assertSame(self::PARENT, $result->data->opportunityId);
        $wire = $this->body($request);
        foreach (['company_id', 'actor_id', 'sandbox', 'id', 'effect_id', 'link_id', 'document_uuid', 'owner_revision', 'confirmed'] as $field) {
            self::assertArrayNotHasKey($field, $wire);
        }
        if ($method === 'createQuote') {
            self::assertNull($wire['contact_id']);
            self::assertNull($wire['quote_draft']['notes']);
            self::assertArrayNotHasKey('terms', $wire['quote_draft']);
        }
        if ($method === 'unlinkDocument') {
            self::assertSame(['expected_version' => 6], $wire);
        }
    }
    public function test_http200_create_quote_and_link_are_unconfirmed_and_recovery_requires_native_http201(): void
    {
        foreach (['createQuote', 'linkDocument'] as $method) {
            $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($this->link(), 200), $this->json($this->link(), 201)]));
            try {
                if ($method === 'createQuote') {
                    $client->createQuote(self::PARENT, new R\CreateOpportunityQuoteRequest(new R\QuoteDraft('2026-10-09', 'unassigned')), self::KEY);
                } else {
                    $client->linkDocument(self::PARENT, new R\LinkOpportunityDocumentRequest('quote', self::OWNER, false), self::KEY);
                }
                self::fail('The native endpoint requires HTTP201.');
            } catch (DocumentWriteUnconfirmed $error) {
                self::assertCount(1, $this->history);
                self::assertSame(201, $client->recover($error->intent)->statusCode());
            }
            self::assertSame((string) $this->history[0]['request']->getBody(), (string) $this->history[1]['request']->getBody());
            self::assertSame(self::KEY, $this->history[1]['request']->getHeaderLine('Idempotency-Key'));
        }
    }
    public function test_existing_primary_link_keeps_original_server_link_and_owner_versions_when_nonprimary_was_requested(): void
    {
        $wire = $this->link();
        $wire['version'] = 12;
        $wire['is_primary'] = true;
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($wire, 201)]));
        $result = $client->linkDocument(self::PARENT, new R\LinkOpportunityDocumentRequest('quote', strtolower(self::OWNER), false), self::KEY);
        self::assertTrue($result->data->isPrimary);
        self::assertSame(12, $result->data->version);
        $document = $result->data->document;
        self::assertNotNull($document);
        self::assertSame(7, $document->version);
        self::assertSame('125.50', $document->quotedAmount);
    }
    public function test_nonfiscal_receipts_retain_native_dates_and_null_amounts(): void
    {
        foreach (['contract', 'recurring_schedule'] as $type) {
            $wire = $this->link($type);
            $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($wire, 201)]));
            $document = $client->linkDocument(self::PARENT, new R\LinkOpportunityDocumentRequest($type, self::OWNER, false), self::KEY)->data->document;
            self::assertNotNull($document);
            self::assertSame('not_applicable', $document->financialApplicability);
            self::assertNull($document->quotedAmount);
            self::assertNull($document->invoicedAmount);
            self::assertNull($document->paidAmount);
            self::assertSame('2026-12-31', $document->validUntil);
        }
    }
    /** @return array<string,array{int,string}> */
    public static function uncertainStatuses(): array
    {
        return ['server_error' => [500, 'internal_error'], 'access_denied' => [403, 'crm_access_denied'], 'not_found' => [404, 'resource_not_found'], 'unconfirmed' => [409, 'crm_effect_unconfirmed']];
    }
    #[DataProvider('uncertainStatuses')]
    public function test_postdispatch_http_uncertainty_never_retries_and_explicit_recovery_reuses_original_body_key_and_cas(int $status, string $code): void
    {
        $client = FactuareaClient::libraryDocuments($this->sdk([$this->error($status, $code, $code === 'crm_effect_unconfirmed' ? 'crm_commercial_document_result_unconfirmed' : null), $this->json(['id' => self::DOCUMENT, 'version' => 7], 200, ['Idempotent-Replayed' => 'true'])]));
        try {
            $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY);
            self::fail('The result must remain unconfirmed.');
        } catch (DocumentWriteUnconfirmed $error) {
            self::assertCount(1, $this->history);
            self::assertSame(self::KEY, $error->intent->idempotencyKey);
            self::assertSame(6, $error->intent->expectedVersion);
            self::assertInstanceOf(Errors\ErrorThrowable::class, $error->cause);
            $result = $client->recover($error->intent);
        }
        self::assertCount(2, $this->history);
        self::assertSame((string) $this->history[0]['request']->getBody(), (string) $this->history[1]['request']->getBody());
        self::assertSame(self::KEY, $this->history[1]['request']->getHeaderLine('Idempotency-Key'));
        self::assertSame(7, $result->data->version);
        self::assertSame('true', $result->headers()['Idempotent-Replayed'][0]);
    }
    public function test_timeout_leaves_the_original_intent_without_redispatch(): void
    {
        $client = FactuareaClient::opportunityDocuments($this->sdk([new ConnectException('Unconfirmed timeout.', new Request('POST', 'https://sdk.invalid/v1'))]));
        try {
            $client->linkDocument(self::PARENT, new R\LinkOpportunityDocumentRequest('quote', self::OWNER, false), self::KEY);
            self::fail('Timeout is not confirmation.');
        } catch (DocumentWriteUnconfirmed $error) {
            self::assertInstanceOf(ConnectException::class, $error->cause);
            self::assertSame(self::KEY, $error->intent->idempotencyKey);
            self::assertSame(self::OWNER, $error->intent->documentId);
        }
        self::assertCount(1, $this->history);
    }
    /** @return array<string,array{int,string}> */
    public static function rejectedStatuses(): array
    {
        return ['CAS' => [409, 'crm_version_conflict'], 'fields' => [422, 'business_rule_violation'], 'quota' => [429, 'rate_limit_exceeded']];
    }
    #[DataProvider('rejectedStatuses')]
    public function test_native_cas_validation_and_quota_errors_remain_typed_without_automatic_retry(int $status, string $code): void
    {
        $client = FactuareaClient::libraryDocuments($this->sdk([$this->error($status, $code)]));
        try {
            $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY);
            self::fail('A native rejection must remain typed.');
        } catch (Errors\ErrorThrowable $error) {
            self::assertSame($code, $error->container->error->code);
            self::assertSame('req_documents', $error->container->error->requestId);
            self::assertSame($status, $error->container->rawResponse?->getStatusCode());
        }
        self::assertCount(1, $this->history);
    }
    /** @return array<string,array{string,mixed}> */
    public static function invalidLinkReceipts(): array
    {
        return ['foreign parent' => ['opportunity_id', self::DOCUMENT], 'wrong owner' => ['document_id', self::REFERENCE], 'string version' => ['version', '7'],
            'floating version' => ['version', 7.5], 'pending' => ['status', 'pending'], 'masked owner' => ['document', null], 'foreign type' => ['document_type', 'invoice'],
            'bad boolean' => ['is_primary', 1], 'private field' => ['effect_id', self::REFERENCE]];
    }
    #[DataProvider('invalidLinkReceipts')]
    public function test_http201_with_foreign_partial_or_queued_receipt_is_never_a_success(string $field, mixed $value): void
    {
        $wire = $this->link();
        $wire[$field] = $value;
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($wire, 201)]));
        $this->expectException(DocumentWriteUnconfirmed::class);
        $client->linkDocument(self::PARENT, new R\LinkOpportunityDocumentRequest('quote', self::OWNER, false), self::KEY);
    }
    public function test_unlink_receipt_requires_the_original_link_cas_plus_one(): void
    {
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json(['id' => self::LINK, 'version' => 9, 'opportunity_id' => self::PARENT, 'document_type' => 'quote', 'document_id' => self::OWNER])]));
        $this->expectException(DocumentWriteUnconfirmed::class);
        $client->unlinkDocument(self::PARENT, self::LINK, new R\CasRequest(6), self::KEY);
    }
    public function test_library_receipt_cannot_confirm_a_newer_head_or_a_private_uuid_field(): void
    {
        foreach ([['id' => self::DOCUMENT, 'version' => 9], ['uuid' => self::DOCUMENT, 'version' => 7]] as $wire) {
            $client = FactuareaClient::libraryDocuments($this->sdk([$this->json($wire)]));
            try {
                $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY);
                self::fail('A nonoriginal receipt is not confirmed.');
            } catch (DocumentWriteUnconfirmed) {
                self::assertCount(1, $this->history);
            }
        }
    }
    public function test_library_read_keeps_omitted_masks_separate_from_explicit_null_and_boolean_query_text(): void
    {
        $client = FactuareaClient::libraryDocuments($this->sdk([$this->json(['items' => [['id' => self::DOCUMENT, 'version' => 6, 'archived_at' => null]], 'total' => 26, 'page' => 2, 'per_page' => 25])]));
        $result = $client->listDocuments(2, 25, sort: 'id', archived: false)->data;
        self::assertSame(Omitted::Value, $result->items[0]->title);
        self::assertNull($result->items[0]->archivedAt);
        parse_str($this->history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('false', $query['archived']);
        self::assertSame('id', $query['sort']);
        self::assertSame('', $this->history[0]['request']->getBody()->getContents());
    }
    public function test_private_download_returns_native_bytes_with_pinned_document_version_and_no_public_url(): void
    {
        $client = FactuareaClient::libraryDocuments($this->sdk([new Response(200, ['Content-Type' => 'application/pdf', 'Cache-Control' => 'private, no-store'], '%PDF-private')]));
        $response = $client->downloadDocument(self::DOCUMENT, 4);
        self::assertSame('%PDF-private', (string) $response->data);
        self::assertSame('private, no-store', $response->headers()['Cache-Control'][0]);
        self::assertSame('/v1/crm/documents/'.self::DOCUMENT.'/download', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('document_version=4', $this->history[0]['request']->getUri()->getQuery());
    }
    public function test_download_redirect_is_not_followed_to_an_external_url(): void
    {
        $client = FactuareaClient::libraryDocuments($this->sdk([new Response(302, ['Location' => 'https://other.invalid/private'])]));
        $this->expectException(Errors\APIException::class);
        $client->downloadDocument(self::DOCUMENT);
    }
    public function test_candidate_unavailability_preserves_owner_reason_and_never_fabricates_a_record_or_cursor(): void
    {
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json(['opportunity_id' => self::PARENT, 'document_type' => 'recurring_schedule', 'items' => [], 'next_cursor' => null, 'available' => false, 'unavailable_reason' => 'crm_recurring_schedule_document_owner_unavailable'])]));
        $result = $client->listCandidates(self::PARENT, 'recurring_schedule')->data;
        self::assertFalse($result->available);
        self::assertSame([], $result->items);
        self::assertNull($result->nextCursor);
        self::assertSame('crm_recurring_schedule_document_owner_unavailable', $result->unavailableReason);
        self::assertSame('/v1/crm/opportunities/'.self::PARENT.'/documents/candidates', $this->history[0]['request']->getUri()->getPath());
    }
    public function test_cursor_iteration_follows_all_pages_with_original_typed_filters(): void
    {
        $first = ['opportunity_id' => self::PARENT, 'items' => [$this->link()], 'next_cursor' => 'native:opaque/a+b=', 'total' => 2, 'pending_count' => 0];
        $second = $first;
        $second['next_cursor'] = null;
        $second['items'][0]['id'] = self::REFERENCE;
        $second['items'][0]['document_id'] = '550e8400-e29b-41d4-a716-446655440001';
        $second['items'][0]['document']['id'] = '550e8400-e29b-41d4-a716-446655440001';
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($first), $this->json($second)]));
        $items = iterator_to_array($client->iterateDocuments(self::PARENT, new R\OpportunityDocumentFilters('quote', isPrimary: false, sort: 'document_id')));
        self::assertCount(2, $items);
        self::assertCount(2, $this->history);
        parse_str($this->history[1]['request']->getUri()->getQuery(), $query);
        self::assertSame('native:opaque/a+b=', $query['cursor']);
        self::assertSame(['document_type' => 'quote', 'is_primary' => false, 'sort' => 'document_id'], json_decode($query['filters'], true, flags: JSON_THROW_ON_ERROR));
    }
    public function test_repeated_native_cursor_fails_instead_of_looping_or_reporting_complete_iteration(): void
    {
        $page = ['opportunity_id' => self::PARENT, 'items' => [], 'next_cursor' => 'same:opaque', 'total' => 2, 'pending_count' => 0];
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($page), $this->json($page)]));
        $this->expectException(LogicException::class);
        iterator_to_array($client->iterateDocuments(self::PARENT));
    }
    public function test_financial_summary_keeps_native_precision_and_separate_currency_buckets(): void
    {
        $buckets = [['currency' => 'EUR', 'forecast' => '0.0001', 'quoted' => '125.50', 'invoiced' => '0.00', 'paid' => '0.00'], ['currency' => 'USD', 'forecast' => '1.5000', 'quoted' => '2.00', 'invoiced' => '1.00', 'paid' => '0.50']];
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json(['opportunity_id' => self::PARENT, 'version' => 8, 'currencies' => $buckets])]));
        self::assertSame($buckets, $client->financialSummary(self::PARENT)->data->currencies);
    }
    public function test_numeric_native_amount_is_not_coerced_into_a_decimal_string(): void
    {
        $wire = $this->link();
        $wire['document']['quoted_amount'] = 125.5;
        $client = FactuareaClient::opportunityDocuments($this->sdk([$this->json($wire, 201)]));
        $this->expectException(DocumentWriteUnconfirmed::class);
        $client->linkDocument(self::PARENT, new R\LinkOpportunityDocumentRequest('quote', self::OWNER, false), self::KEY);
    }
    public function test_recovery_freezes_effective_profile_and_version_after_original_hooks(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error'), $this->json(['id' => self::DOCUMENT, 'version' => 7])]);
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class implements \Factuarea\Sdk\Hooks\BeforeRequestHook
        {
            private int $calls = 0;
            public function beforeRequest(\Factuarea\Sdk\Hooks\BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->calls++;

                return $request->withHeader('X-Active-Profile', $this->calls === 1 ? '01900000-0000-7000-8000-000000000004' : '01900000-0000-7000-8000-000000000002')->withHeader('Factuarea-Version', $this->calls === 1 ? '2026-09-01' : '2026-10-01');
            }
        });
        $client = FactuareaClient::libraryDocuments($sdk);
        try {
            $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY);
            self::fail('Unconfirmed result required.');
        } catch (DocumentWriteUnconfirmed $error) {
            $client->recover($error->intent);
        }
        self::assertSame(self::REFERENCE, $this->history[1]['request']->getHeaderLine('X-Active-Profile'));
        self::assertSame('2026-09-01', $this->history[1]['request']->getHeaderLine('Factuarea-Version'));
    }
    public function test_recovery_cannot_cross_server_or_credentials(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error')]);
        $client = FactuareaClient::libraryDocuments($sdk);
        try {
            $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY);
            self::fail('Unconfirmed result required.');
        } catch (DocumentWriteUnconfirmed $error) {
            $sdk->sdkConfiguration->serverUrl = 'https://other.invalid/v1';
            $this->expectException(InvalidArgumentException::class);
            $client->recover($error->intent);
        }
    }
    public function test_hooks_cannot_replace_original_method_path_body_key_or_fabricate_confirmation(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error')]);
        $hook = new class implements \Factuarea\Sdk\Hooks\AfterErrorHook, \Factuarea\Sdk\Hooks\BeforeRequestHook
        {
            public int $afterCalls = 0;
            public function beforeRequest(\Factuarea\Sdk\Hooks\BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                return new Request('PUT', 'https://other.invalid/fake', ['Idempotency-Key' => 'fresh-key'], '{"company_id":99,"expected_version":99}');
            }
            public function afterError(\Factuarea\Sdk\Hooks\AfterErrorContext $context, ?\Psr\Http\Message\ResponseInterface $response, ?\Throwable $error): \Factuarea\Sdk\Hooks\ErrorResponseContext
            {
                $this->afterCalls++;

                return new \Factuarea\Sdk\Hooks\ErrorResponseContext(new Response(200, ['Content-Type' => 'application/json'], '{}'), null);
            }
        };
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook($hook);
        $sdk->sdkConfiguration->hooks->registerAfterErrorHook($hook);
        $client = FactuareaClient::libraryDocuments($sdk);
        try {
            $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY);
            self::fail('A hook is not a receipt.');
        } catch (DocumentWriteUnconfirmed) {
            self::assertSame(0, $hook->afterCalls);
        }
        $request = $this->history[0]['request'];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('sdk.invalid', $request->getUri()->getHost());
        self::assertSame('/v1/crm/documents/'.self::DOCUMENT.'/archive', $request->getUri()->getPath());
        self::assertSame(self::KEY, $request->getHeaderLine('Idempotency-Key'));
        self::assertSame(['expected_version' => 6], $this->body($request));
    }
    public function test_invalid_new_crm_identity_is_rejected_locally_while_historical_owner_identity_remains_allowed(): void
    {
        $client = FactuareaClient::opportunityDocuments($this->sdk([]));
        new R\LinkOpportunityDocumentRequest('quote', self::OWNER, false);
        $this->expectException(InvalidArgumentException::class);
        $client->listDocuments(self::OWNER);
    }
    public function test_masked_missing_field_is_allowed_but_present_nonnullable_null_is_not_invented_as_omission(): void
    {
        $client = FactuareaClient::libraryDocuments($this->sdk([$this->json(['id' => self::DOCUMENT, 'version' => 6, 'title' => null])]));
        $this->expectException(Errors\APIException::class);
        $client->getDocument(self::DOCUMENT);
    }

    public function test_recovery_cannot_use_another_credential_binding_even_with_the_same_server(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error')]);
        $client = FactuareaClient::libraryDocuments($sdk);
        try {
            $client->archiveDocument(self::DOCUMENT, new R\CasRequest(6), self::KEY);
            self::fail('Unconfirmed result required.');
        } catch (DocumentWriteUnconfirmed $error) {
            $sdk->sdkConfiguration->securitySource = function (): Components\Security {
                return new Components\Security(bearerAuth: 'fact_test_other_company');
            };
            $this->expectException(InvalidArgumentException::class);
            $client->recover($error->intent);
        }
    }

    public function test_expiration_omission_and_null_remain_distinct(): void
    {
        $without = new R\ShareDocumentRequest(6, 'user', self::OWNER);
        $withNull = new R\ShareDocumentRequest(6, 'user', self::OWNER, null);
        self::assertArrayNotHasKey('expires_at', $without->toArray());
        self::assertArrayHasKey('expires_at', $withNull->toArray());
        self::assertNull($withNull->toArray()['expires_at']);
        $withoutContact = new R\CreateOpportunityQuoteRequest(new R\QuoteDraft('2026-10-09', 'unassigned'));
        self::assertArrayNotHasKey('contact_id', $withoutContact->toArray());
        self::assertArrayNotHasKey('id', $withoutContact->toArray()['quote_draft']['recipient']);
    }
    /** @return array<string,mixed> */
    private function link(string $type = 'quote'): array
    {
        $fiscal = in_array($type, ['quote', 'invoice'], true);

        return ['id' => self::LINK, 'version' => 2, 'opportunity_id' => self::PARENT, 'document_type' => $type, 'document_id' => self::OWNER, 'is_primary' => false, 'status' => 'linked',
            'document' => ['id' => self::OWNER, 'type' => $type, 'version' => 7, 'state' => $fiscal ? 'approved' : 'active', 'number' => $fiscal ? 'Q-7' : null, 'currency' => 'EUR', 'quoted_amount' => $fiscal ? '125.50' : null, 'invoiced_amount' => $fiscal ? '0.00' : null, 'paid_amount' => $fiscal ? '0.00' : null, 'financial_applicability' => $fiscal ? 'applicable' : 'not_applicable', 'valid_from' => $fiscal ? null : '2026-01-01', 'valid_until' => $fiscal ? null : '2026-12-31']];
    }
    /**
     * @param  array<string,mixed>  $data
     * @param  array<string,string>  $headers
     */
    private function json(array $data, int $status = 200, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', ...$headers], json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }
    private function error(int $status, string $code, ?string $subcode = null): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode(['error' => ['type' => $status >= 500 ? 'api_error' : 'invalid_request_error', 'code' => $code, 'subcode' => $subcode, 'message' => 'Native rejection.', 'request_id' => 'req_documents']], JSON_THROW_ON_ERROR));
    }
    /** @return array<string,mixed> */
    private function body(RequestInterface $request): array
    {
        $value = json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR);
        if (!is_array($value)) {
            throw new LogicException('Invalid request fixture.');
        }

        return $value;
    }
}
