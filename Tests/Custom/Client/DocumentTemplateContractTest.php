<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Client;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Request\NoRetry;
use Factuarea\Sdk\Custom\Request\RawRequest;
use Factuarea\Sdk\Custom\Request\RequestOverrides;
use Factuarea\Sdk\Custom\Request\RequestOverridesClient;
use Factuarea\Sdk\Custom\Response\ResponseMeta;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\BulkPdfInvoicesV1Request;
use Factuarea\Sdk\Models\Components\CancelFaceSubmissionV1Request;
use Factuarea\Sdk\Models\Components\QuarterlyDownloadV1Request;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Components\UpdateSeriesRequest;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\Models\Operations\PublicApiV1FaceSubmissionsCancelRequest;
use Factuarea\Sdk\Models\Operations\PublicApiV1InvoicesPdfRequest;
use Factuarea\Sdk\Models\Operations\PublicApiV1SeriesUpdateRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Speakeasy\Serializer\Exception\PropertyMissingException;

/**
 * Wire and response handling of the document-template contract (client flows
 * CE01-CE25 of the `document-template-client-surface-parity` acceptance
 * handoff) against what the pinned generated SDK can invoke today, with a mocked
 * transport and no network.
 *
 * Operations without a typed method (the whole `/pdf-*` family, presentation,
 * administrative revisions) go through {@see RawRequest}; the existing operations
 * are called with their generated methods plus {@see RequestOverrides} and
 * {@see NoRetry}. Every company operation hangs off `/v1/companies/{company}/…`:
 * the raw calls fill the `{company}` path parameter with the company `id` and
 * the generated methods take it as their `company` argument. Scenarios that are
 * server-side rules (roles, tenants,
 * privacy, quotas) or that need a surface the SDK does not have are not
 * repeated here: see the CE coverage map in `docs/REGENERATION.md`.
 *
 * CE01/CE02/CE07 library, brand and preview     -> test_ce01_*, test_raw_* (same wire shape).
 * CE03/CE04 defaults and contact preference     -> test_ce03_*, test_ce04_*.
 * CE05 series                                   -> test_ce05_*.
 * CE06/CE24 presentation state and revision     -> test_ce06_*, test_ce24_*.
 * CE08 multipart assets                         -> test_ce08_*.
 * CE09/CE12/CE13 create, issue, terms           -> test_ce09_*, test_ce12_*.
 * CE11 administrative routing                   -> test_ce11_*.
 * CE14 current XML                              -> test_ce14_*.
 * CE15 FACe                                     -> test_ce15_*.
 * CE16/CE17 PDF statuses and artifacts          -> test_ce16_*, test_ce17_*.
 * CE20/CE21 signature forget                    -> test_ce21_*.
 * CE22 send                                     -> test_ce22_*.
 * CE23 ZIP                                      -> test_ce23_*.
 * CE25 headers, errors, paging                  -> test_ce25_*.
 */
final class DocumentTemplateContractTest extends TestCase
{
    private const JSON = ['Content-Type' => 'application/json'];

    /** Company axis of the v1 contract: the company `id` (UUID v7) travels in the URL. */
    private const COMPANY = '019e0000-0000-7000-8000-0000000000c0';

    private const BASE = '/v1/companies/'.self::COMPANY;

    /** @var list<array{request: RequestInterface}> */
    private array $history = [];

    private function sdk(Response|\Throwable ...$responses): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler(array_values($responses)));
        $stack->push(Middleware::history($this->history));

        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_x'))
            ->setClient(new RequestOverridesClient(new IdempotencyClient(new Client(['handler' => $stack]))))
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());

        return $sdk;
    }

    private function request(int $index = 0): RequestInterface
    {
        return $this->history[$index]['request'];
    }

    private function json(int $status, mixed $body, array $headers = []): Response
    {
        return new Response($status, self::JSON + $headers, (string) json_encode($body));
    }

    /** @return array<string, mixed> */
    private function error(string $code): array
    {
        return ['error' => ['type' => 'invalid_request_error', 'code' => $code, 'message' => 'x', 'request_id' => 'req_1']];
    }

    /** @return array<string, mixed> */
    private function face(string $status = 'submitted'): array
    {
        return ['id' => 'f1', 'object' => 'face_submission', 'invoice_id' => 'i1', 'status' => $status,
            'dir3_accounting_office' => 'A', 'dir3_managing_body' => 'B', 'dir3_processing_unit' => 'C',
            'status_updated_at' => '2026-10-01T10:00:00+00:00', 'created_at' => '2026-10-01T10:00:00+00:00',
            'registry_number' => null, 'error_code' => null, 'error_message' => null, 'last_polled_at' => null];
    }

    private function meta(Response $response): ResponseMeta
    {
        return ResponseMeta::fromResponse($response);
    }

    // ---------------------------------------------------------------- RawRequest

    public function test_raw_request_sends_auth_version_accept_and_an_automatic_idempotency_key(): void
    {
        $sdk = $this->sdk($this->json(201, ['id' => 't1']));

        $response = RawRequest::send($sdk, 'post', '/companies/{company}/pdf-templates', ['json' => ['name' => 'Mi plantilla']], ['company' => self::COMPANY]);

        $this->assertSame(201, $response->getStatusCode());
        $request = $this->request();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://api.factuarea.com'.self::BASE.'/pdf-templates', (string) $request->getUri());
        $this->assertSame('Bearer fact_test_x', $request->getHeaderLine('Authorization'));
        $this->assertSame(FactuareaVersionHook::DEFAULT_VERSION, $request->getHeaderLine('Factuarea-Version'));
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
        $this->assertStringStartsWith('speakeasy-sdk/php', $request->getHeaderLine('User-Agent'));
        $this->assertMatchesRegularExpression('/^[0-9a-f-]{36}$/', $request->getHeaderLine('Idempotency-Key'));
        $this->assertSame('{"name":"Mi plantilla"}', (string) $request->getBody());
    }

    public function test_raw_request_encodes_path_parameters_and_never_throws_on_http_errors(): void
    {
        $sdk = $this->sdk($this->json(404, $this->error('resource_not_found')));

        $response = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-templates/{template}/revisions/{revision}', [], ['company' => self::COMPANY, 'template' => 'a/b c', 'revision' => 7]);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame(self::BASE.'/pdf-templates/a%2Fb%20c/revisions/7', $this->request()->getUri()->getPath());
        $this->assertSame('resource_not_found', $this->meta($response)->json()['error']['code']);
    }

    public function test_raw_request_lets_the_caller_override_accept_and_version_case_insensitively(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/pdf'], '%PDF'));

        RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-artifacts/{artifact}/download', ['headers' => ['accept' => 'application/pdf', 'FACTUAREA-VERSION' => '2026-09-01']], ['company' => self::COMPANY, 'artifact' => 'a1']);

        $this->assertSame(['application/pdf'], $this->request()->getHeader('Accept'));
        $this->assertSame(['2026-09-01'], $this->request()->getHeader('Factuarea-Version'));
    }

    public function test_raw_request_rejects_a_missing_path_parameter_and_a_non_relative_path(): void
    {
        $sdk = $this->sdk();

        try {
            RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-templates/{template}', [], ['company' => self::COMPANY]);
            $this->fail('A missing path parameter must be refused.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('{template}', $e->getMessage());
        }

        $this->expectException(\InvalidArgumentException::class);
        RawRequest::send($sdk, 'GET', 'companies/{company}/pdf-templates', [], ['company' => self::COMPANY]);
    }

    // ---------------------------------------------------------------- CE01 / CE03 / CE04

    public function test_ce01_update_sends_the_literal_etag_in_if_match_and_an_explicit_null(): void
    {
        $sdk = $this->sdk($this->json(200, ['id' => 't1', 'version' => 4], ['ETag' => 'W/"tpl-4"']));

        $response = RawRequest::send($sdk, 'PATCH', '/companies/{company}/pdf-templates/{template}', [
            'headers' => ['If-Match' => 'W/"tpl-3"'],
            'json' => ['expected_version' => 3, 'description' => null],
        ], ['company' => self::COMPANY, 'template' => 't1']);

        $this->assertSame('W/"tpl-3"', $this->request()->getHeaderLine('If-Match'));
        $this->assertSame('{"expected_version":3,"description":null}', (string) $this->request()->getBody());
        $this->assertSame('W/"tpl-4"', $this->meta($response)->etag);
    }

    public function test_ce03_defaults_roundtrip_keeps_the_etag_and_a_stale_tag_is_one_call_without_retry(): void
    {
        $sdk = $this->sdk(
            $this->json(200, ['id' => 'd1', 'version' => 2, 'render_activation' => 'legacy'], ['ETag' => '"d-2"']),
            $this->json(412, $this->error('pdf_template_precondition_failed')),
        );

        $get = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-templates/defaults', [], ['company' => self::COMPANY]);
        $etag = $this->meta($get)->etag;
        $patch = RawRequest::send($sdk, 'PATCH', '/companies/{company}/pdf-templates/defaults', [
            'headers' => ['If-Match' => (string) $etag],
            'json' => ['expected_version' => 2, 'type_defaults' => [], 'company_template_id' => null],
        ], ['company' => self::COMPANY]);

        $this->assertCount(2, $this->history);
        $this->assertSame('', $this->request(0)->getHeaderLine('If-Match'));
        $this->assertSame('', $this->request(0)->getHeaderLine('Idempotency-Key'));
        $this->assertSame('"d-2"', $this->request(1)->getHeaderLine('If-Match'));
        $this->assertSame('{"expected_version":2,"type_defaults":[],"company_template_id":null}', (string) $this->request(1)->getBody());
        $this->assertSame(412, $patch->getStatusCode());
    }

    public function test_ce04_contact_default_distinguishes_clearing_from_omitting(): void
    {
        $sdk = $this->sdk($this->json(200, ['template_id' => null]), $this->json(200, ['template_id' => 'x']));
        $path = '/companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}';
        $params = ['company' => self::COMPANY, 'contact' => 'c1', 'document_type' => 'invoice', 'purpose' => 'complete'];

        RawRequest::send($sdk, 'PATCH', $path, ['headers' => ['If-Match' => '"c"'], 'json' => ['expected_version' => 1, 'template_id' => null]], $params);
        RawRequest::send($sdk, 'PATCH', $path, ['headers' => ['If-Match' => '"c"'], 'json' => ['expected_version' => 1]], $params);

        $this->assertSame(self::BASE.'/pdf-templates/contact-defaults/c1/invoice/complete', $this->request(0)->getUri()->getPath());
        $this->assertSame('{"expected_version":1,"template_id":null}', (string) $this->request(0)->getBody());
        $this->assertSame('{"expected_version":1}', (string) $this->request(1)->getBody());
    }

    // ---------------------------------------------------------------- CE05

    public function test_ce05_series_patch_update_carries_if_match_and_clears_the_template_with_the_generated_method(): void
    {
        $sdk = $this->sdk($this->json(200, ['data' => []]));

        try {
            RequestOverrides::run(
                fn () => $sdk->series->publicApiV1SeriesUpdate(new PublicApiV1SeriesUpdateRequest(company: self::COMPANY, series: 's1', body: new UpdateSeriesRequest(name: 'B2B'))),
                clear: ['pdf_template_id'],
                headers: ['If-Match' => '"s-3"'],
            );
        } catch (\Throwable) {
            // Only the outgoing request is measured: the empty `data` is not a Series.
        }

        $request = $this->request();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame(self::BASE.'/series/s1', $request->getUri()->getPath());
        $this->assertSame('"s-3"', $request->getHeaderLine('If-Match'));
        $this->assertSame('{"name":"B2B","pdf_template_id":null}', (string) $request->getBody());
    }

    public function test_ce05_series_pdf_template_subresource_is_a_separate_patch_with_its_own_cas(): void
    {
        $sdk = $this->sdk($this->json(428, $this->error('series_template_precondition_required')));

        $response = RawRequest::send($sdk, 'PATCH', '/companies/{company}/series/{series}/pdf-template', [
            'headers' => ['If-Match' => '"vis-1"'],
            'json' => ['pdf_template_id' => '0199aaaa-0000-7000-8000-000000000001', 'expected_version' => 1],
        ], ['company' => self::COMPANY, 'series' => 's1']);

        $this->assertSame('PATCH', $this->request()->getMethod());
        $this->assertSame(self::BASE.'/series/s1/pdf-template', $this->request()->getUri()->getPath());
        $this->assertSame(428, $response->getStatusCode());
        $this->assertCount(1, $this->history, '428 is not retried');
    }

    // ---------------------------------------------------------------- CE06 / CE24

    public function test_ce06_presentation_state_and_history_use_page_and_per_page(): void
    {
        $sdk = $this->sdk($this->json(200, ['data' => []], ['ETag' => '"p-1"']), $this->json(200, ['data' => [], 'meta' => ['page' => 2]]));

        RawRequest::send($sdk, 'GET', '/companies/{company}/invoices/{invoice}/presentation', [], ['company' => self::COMPANY, 'invoice' => 'i1']);
        RawRequest::send($sdk, 'GET', '/companies/{company}/invoices/{invoice}/presentation-history', ['query' => ['page' => 2, 'per_page' => 25]], ['company' => self::COMPANY, 'invoice' => 'i1']);

        $this->assertSame('', $this->request(0)->getUri()->getQuery());
        $this->assertSame('page=2&per_page=25', $this->request(1)->getUri()->getQuery());
        $this->assertSame('', (string) $this->request(1)->getBody());
        $this->assertSame('', $this->request(1)->getHeaderLine('Idempotency-Key'), 'GETs carry no write headers');
    }

    public function test_ce24_presentation_revision_sends_the_source_token_and_surfaces_a_stale_legal_source(): void
    {
        $sdk = $this->sdk($this->json(409, $this->error('pdf_legal_source_changed')));

        $response = RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/{invoice}/presentation-revisions', ['json' => [
            'parent_id' => '0199aaaa-0000-7000-8000-000000000001',
            'expected_version' => 3,
            'reason' => 'Revisión legal',
            'expected_source_token' => 'tok',
            'expected_preview_context_fingerprint' => 'fp',
            'source_policy' => 'current_legal',
        ]], ['company' => self::COMPANY, 'invoice' => 'i1']);

        $this->assertSame(self::BASE.'/invoices/i1/presentation-revisions', $this->request()->getUri()->getPath());
        $this->assertSame('current_legal', json_decode((string) $this->request()->getBody(), true)['source_policy']);
        $this->assertSame(409, $response->getStatusCode());
        $this->assertSame('pdf_legal_source_changed', $this->meta($response)->json()['error']['code']);
    }

    // ---------------------------------------------------------------- CE08

    public function test_ce08_asset_upload_keeps_the_multipart_bytes_exactly(): void
    {
        $bytes = "\x89PNG\r\n\x1a\n\x00\xff\xfe-binary";
        $sdk = $this->sdk($this->json(201, ['id' => 'as1']));

        RawRequest::send($sdk, 'POST', '/companies/{company}/pdf-templates/assets', ['multipart' => [
            ['name' => 'file', 'contents' => $bytes, 'filename' => 'logo.png', 'headers' => ['Content-Type' => 'image/png']],
            ['name' => 'kind', 'contents' => 'logo'],
        ]], ['company' => self::COMPANY]);

        $request = $this->request();
        $this->assertMatchesRegularExpression('#^multipart/form-data; boundary=#', $request->getHeaderLine('Content-Type'));
        $this->assertStringContainsString($bytes, (string) $request->getBody());
        $this->assertStringContainsString('filename="logo.png"', (string) $request->getBody());
        $this->assertNotSame('', $request->getHeaderLine('Idempotency-Key'));
    }

    // ---------------------------------------------------------------- CE09 / CE12 / CE13

    public function test_ce09_issue_with_presentation_preconditions_needs_the_raw_body_and_a_428_is_an_api_exception_for_the_generated_method(): void
    {
        // Generated method: no body, so a company with the presentation active gets 428.
        $sdk = $this->sdk($this->json(428, $this->error('pdf_presentation_precondition_required')));
        try {
            $sdk->invoices->publicApiV1InvoicesIssue(self::COMPANY, 'i1', 'key-1');
            $this->fail('A 428 must raise.');
        } catch (APIException $e) {
            $this->assertSame(428, $e->statusCode);
            $this->assertSame('pdf_presentation_precondition_required', json_decode($e->body, true)['error']['code']);
        }
        $this->assertSame('', (string) $this->request()->getBody());

        // Raw call: the same operation with the preconditions and the caller's key.
        $sdk = $this->sdk($this->json(200, ['data' => ['id' => 'i1']], ['Idempotent-Replayed' => 'true']));
        $response = RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/{invoice}/issue', [
            'headers' => ['Idempotency-Key' => 'key-1'],
            'json' => ['expected_selection_version' => 4, 'expected_source_token' => 'tok', 'expected_preview_context_fingerprint' => 'fp', 'expected_template_revision_id' => null],
        ], ['company' => self::COMPANY, 'invoice' => 'i1']);

        $this->assertSame('key-1', $this->request()->getHeaderLine('Idempotency-Key'));
        $this->assertSame('true', $response->getHeaderLine('Idempotent-Replayed'));
        $this->assertSame(
            '{"expected_selection_version":4,"expected_source_token":"tok","expected_preview_context_fingerprint":"fp","expected_template_revision_id":null}',
            (string) $this->request()->getBody(),
        );
    }

    public function test_ce12_payment_terms_omit_null_and_empty_list_are_three_different_bodies(): void
    {
        $sdk = $this->sdk($this->json(200, []), $this->json(200, []), $this->json(200, []), $this->json(422, $this->error('invalid_invoice_payment_terms')));

        foreach ([[], ['payment_terms' => null], ['payment_terms' => []]] as $body) {
            RawRequest::send($sdk, 'PATCH', '/companies/{company}/invoices/{invoice}', ['json' => ['notes' => 'x'] + $body], ['company' => self::COMPANY, 'invoice' => 'i1']);
        }
        RawRequest::send($sdk, 'PATCH', '/companies/{company}/invoices/{invoice}', ['json' => ['payment_terms' => [['due_date' => '2026-11-01', 'amount' => '100.00', 'method' => '04']], 'payment_terms_version' => 0]], ['company' => self::COMPANY, 'invoice' => 'i1']);

        $this->assertSame('{"notes":"x"}', (string) $this->request(0)->getBody());
        $this->assertSame('{"notes":"x","payment_terms":null}', (string) $this->request(1)->getBody());
        $this->assertSame('{"notes":"x","payment_terms":[]}', (string) $this->request(2)->getBody());
        $this->assertStringContainsString('"amount":"100.00"', (string) $this->request(3)->getBody());
        $this->assertStringContainsString('"payment_terms_version":0', (string) $this->request(3)->getBody());
    }

    // ---------------------------------------------------------------- CE11

    public function test_ce11_administrative_revision_reads_the_new_version_and_a_missing_version_is_428(): void
    {
        $sdk = $this->sdk(
            $this->json(201, ['data' => ['id' => 'r1', 'version' => 3], 'administrative_version' => 3]),
            $this->json(428, $this->error('invoice_administrative_precondition_required')),
        );
        $body = ['administrative_centres' => [['role' => '01', 'code' => 'L01']], 'reason' => 'Cambio de oficina', 'expected_version' => 2];

        $created = RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/{invoice}/administrative-revisions', ['json' => $body], ['company' => self::COMPANY, 'invoice' => 'i1']);
        $missing = RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/{invoice}/administrative-revisions', ['json' => ['reason' => 'x']], ['company' => self::COMPANY, 'invoice' => 'i1']);

        $this->assertSame(201, $created->getStatusCode());
        $this->assertSame(3, $this->meta($created)->json()['administrative_version']);
        $this->assertSame(428, $missing->getStatusCode());
        $this->assertCount(2, $this->history);
    }

    // ---------------------------------------------------------------- CE14

    public function test_ce14_current_facturae_xml_is_read_with_its_headers_and_a_submission_id_query(): void
    {
        $xml = "<?xml version=\"1.0\"?><fe:Facturae/>\xc3\xa9";
        $sdk = $this->sdk(
            new Response(200, ['Content-Type' => 'application/xml', 'ETag' => '"x1"', 'X-Facturae-Sha256' => hash('sha256', $xml)], $xml),
            $this->json(422, $this->error('business_rule_violation')),
        );

        $current = RawRequest::send($sdk, 'GET', '/companies/{company}/invoices/{invoice}/facturae', ['headers' => ['Accept' => 'application/xml']], ['company' => self::COMPANY, 'invoice' => 'i1']);
        $exact = RawRequest::send($sdk, 'GET', '/companies/{company}/invoices/{invoice}/facturae', ['query' => ['submission_id' => '0199aaaa-0000-7000-8000-000000000001']], ['company' => self::COMPANY, 'invoice' => 'i1']);

        $this->assertSame($xml, (string) $current->getBody());
        $this->assertSame(hash('sha256', $xml), $current->getHeaderLine('X-Facturae-Sha256'));
        $this->assertSame('"x1"', $this->meta($current)->etag);
        $this->assertSame('', $this->request(0)->getUri()->getQuery());
        $this->assertSame('submission_id=0199aaaa-0000-7000-8000-000000000001', $this->request(1)->getUri()->getQuery());
        $this->assertSame(422, $exact->getStatusCode());
    }

    // ---------------------------------------------------------------- CE15

    public function test_ce15_a_first_submission_is_201_and_a_replay_200_is_an_api_exception_that_response_meta_reads(): void
    {
        $sdk = $this->sdk($this->json(201, ['data' => $this->face()]));
        $created = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(self::COMPANY, 'i1', 'key-1');
        $this->assertSame(201, $created->statusCode);
        $this->assertSame('POST', $this->request()->getMethod());
        $this->assertSame('key-1', $this->request()->getHeaderLine('Idempotency-Key'));

        $sdk = $this->sdk($this->json(200, ['data' => $this->face('registered_rcf')], ['Idempotent-Replayed' => 'true']));
        try {
            $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(self::COMPANY, 'i1', 'key-1');
            $this->fail('The generated method does not expect a 200.');
        } catch (APIException $e) {
            $meta = ResponseMeta::fromException($e);
            $this->assertSame(200, $meta->statusCode);
            $this->assertSame('registered_rcf', $meta->json()['data']['status']);
            $this->assertSame('true', $e->rawResponse?->getHeaderLine('Idempotent-Replayed'));
        }
    }

    public function test_ce15_a_502_is_retried_by_the_generated_method_but_not_with_no_retry_options(): void
    {
        $bad = fn () => $this->json(502, $this->error('face_transmission_failed'), ['retry-after-ms' => '1']);

        $sdk = $this->sdk($bad(), $this->json(201, ['data' => $this->face()]));
        $retried = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(self::COMPANY, 'i1', 'key-1');
        $this->assertSame(201, $retried->statusCode);
        $this->assertCount(2, $this->history, 'documents the risk: the generated retry repeats the submission');

        $sdk = $this->sdk($bad(), $this->json(201, ['data' => $this->face()]));
        try {
            $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(self::COMPANY, 'i1', 'key-1', options: NoRetry::options());
            $this->fail('A 502 must raise.');
        } catch (APIException|ErrorThrowable $e) {
            $this->assertInstanceOf(\Throwable::class, $e);
        }
        $this->assertCount(1, $this->history);
    }

    public function test_ce15_cancel_uses_the_same_single_call_and_an_uncertain_409_is_not_retried(): void
    {
        $sdk = $this->sdk($this->json(502, $this->error('face_transmission_failed'), ['Retry-After' => '30']));

        try {
            $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
                new PublicApiV1FaceSubmissionsCancelRequest(company: self::COMPANY, faceSubmission: 'f1', idempotencyKey: 'key-2', body: new CancelFaceSubmissionV1Request(reason: 'Error')),
                NoRetry::options(),
            );
            $this->fail('A 502 must raise.');
        } catch (APIException|ErrorThrowable $e) {
            $this->assertInstanceOf(\Throwable::class, $e);
        }
        $this->assertCount(1, $this->history);

        $sdk = $this->sdk($this->json(409, $this->error('face_transmission_uncertain') + ['attempt_id' => 'a1']));
        $response = RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/{invoice}/face-submissions', ['json' => ['submission_id' => '0199aaaa-0000-7000-8000-000000000001']], ['company' => self::COMPANY, 'invoice' => 'i1']);
        $this->assertSame(409, $response->getStatusCode());
        $this->assertSame('{"submission_id":"0199aaaa-0000-7000-8000-000000000001"}', (string) $this->request()->getBody());
        $this->assertCount(1, $this->history);
    }

    public function test_ce15_a_dropped_connection_is_retried_with_the_same_idempotency_key(): void
    {
        $sdk = $this->sdk(new ConnectException('reset', new Request('POST', 'https://api.factuarea.com/v1')), $this->json(201, ['data' => $this->face()]));

        $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(self::COMPANY, 'i1', 'key-3', options: NoRetry::options());

        $this->assertCount(2, $this->history);
        $this->assertSame('key-3', $this->request(0)->getHeaderLine('Idempotency-Key'));
        $this->assertSame('key-3', $this->request(1)->getHeaderLine('Idempotency-Key'));
    }

    // ---------------------------------------------------------------- CE16

    /** @return array<string, array{0: string, 1: \Closure(Factuarea): mixed}> */
    public static function pdfOperations(): array
    {
        return [
            'invoice' => [self::BASE.'/invoices/d1/pdf', fn (Factuarea $s) => $s->invoices->publicApiV1InvoicesPdf(new PublicApiV1InvoicesPdfRequest(company: self::COMPANY, invoice: 'd1'))],
            'quote' => [self::BASE.'/quotes/d1/pdf', fn (Factuarea $s) => $s->quotes->publicApiV1QuotesPdf(self::COMPANY, 'd1')],
            'proforma' => [self::BASE.'/proformas/d1/pdf', fn (Factuarea $s) => $s->proformas->publicApiV1ProformasPdf(self::COMPANY, 'd1')],
            'delivery note' => [self::BASE.'/delivery-notes/d1/pdf', fn (Factuarea $s) => $s->deliveryNotes->publicApiV1DeliveryNotesPdf(self::COMPANY, 'd1')],
        ];
    }

    #[DataProvider('pdfOperations')]
    public function test_ce16_pdf_200_is_byte_exact_and_a_conditional_get_is_a_zero_byte_304(string $path, \Closure $call): void
    {
        $pdf = "%PDF-1.7\n\x00\xff\xfe binary\n%%EOF";
        $sdk = $this->sdk(
            new Response(200, ['Content-Type' => 'application/pdf', 'ETag' => '"pdf-1"'], $pdf),
            new Response(304, ['ETag' => '"pdf-1"']),
        );

        $first = $call($sdk);
        $second = RequestOverrides::run(fn () => $call($sdk), headers: ['If-None-Match' => '"pdf-1"']);

        $this->assertSame($path, $this->request(0)->getUri()->getPath());
        $this->assertSame(hash('sha256', $pdf), hash('sha256', $first->bytes));
        $this->assertSame('"pdf-1"', $this->meta($first->rawResponse)->etag);
        $this->assertSame('"pdf-1"', $this->request(1)->getHeaderLine('If-None-Match'));
        $this->assertSame(304, $second->statusCode);
        $this->assertNull($second->bytes);
    }

    #[DataProvider('pdfOperations')]
    public function test_ce16_pdf_202_json_is_never_bytes_and_a_503_keeps_retry_after(string $path, \Closure $call): void
    {
        $sdk = $this->sdk($this->json(202, ['data' => ['status' => 'pendiente', 'document_uuid' => 'd1']], ['Retry-After' => '5']));
        try {
            $call($sdk);
            $this->fail('The generated method does not declare a 202.');
        } catch (APIException $e) {
            $meta = ResponseMeta::fromException($e);
            $this->assertSame(202, $meta->statusCode);
            $this->assertSame(5, $meta->retryAfter);
            $this->assertSame('pendiente', $meta->json()['data']['status']);
        }
        $this->assertCount(1, $this->history);
    }

    #[DataProvider('pdfOperations')]
    public function test_ce16_pdf_legal_source_changed_409_and_privacy_410_surface_their_status(string $path, \Closure $call): void
    {
        foreach ([409 => 'pdf_legal_source_changed', 410 => 'pdf_signature_personal_data_forgotten', 422 => 'pdf_presentation_not_ready'] as $status => $code) {
            $sdk = $this->sdk($this->json($status, $this->error($code)));
            try {
                $call($sdk);
                $this->fail("A $status must raise.");
            } catch (APIException $e) {
                $this->assertSame($status, $e->statusCode);
                $this->assertSame($code, json_decode($e->body, true)['error']['code']);
            } catch (ErrorThrowable $e) {
                $this->assertSame($code, $e->container->error->code);
            }
        }
    }

    public function test_ce16_the_two_pdf_link_202_bodies_only_one_of_them_fits_the_generated_model(): void
    {
        $message = 'La generación del PDF está en curso. El enlace estará disponible en unos segundos.';
        $withPath = ['data' => ['status' => 'pendiente', 'document_uuid' => 'd1', 'pdf_path' => '/p', 'pdf_url' => 'https://x'], 'message' => $message];
        $withoutPath = ['data' => ['status' => 'pendiente', 'document_uuid' => 'd1', 'pdf_url' => 'https://x'], 'message' => $message];

        $this->assertSame(202, $this->sdk($this->json(202, $withPath))->invoices->publicApiV1InvoicesPdfLink(self::COMPANY, 'd1')->statusCode);

        $this->expectException(PropertyMissingException::class);
        $this->sdk($this->json(202, $withoutPath))->invoices->publicApiV1InvoicesPdfLink(self::COMPANY, 'd1');
    }

    public function test_ce16_the_pdf_link_202_without_pdf_path_is_readable_through_the_raw_call(): void
    {
        $body = ['data' => ['status' => 'pendiente', 'document_uuid' => 'd1', 'pdf_url' => 'https://x'], 'message' => 'm'];
        $response = RawRequest::send($this->sdk($this->json(202, $body)), 'GET', '/companies/{company}/invoices/{invoice}/pdf-link', ['query' => ['format' => 'a4']], ['company' => self::COMPANY, 'invoice' => 'd1']);

        $this->assertSame(202, $this->meta($response)->statusCode);
        $this->assertSame('https://x', $this->meta($response)->json()['data']['pdf_url']);
        $this->assertSame('format=a4', $this->request()->getUri()->getQuery());
    }

    // ---------------------------------------------------------------- CE17

    public function test_ce17_prepare_poll_and_download_wait_for_ready_with_the_retry_after_of_a_503(): void
    {
        $pdf = "%PDF-1.7\x00\xff";
        $sdk = $this->sdk(
            $this->json(202, ['data' => ['id' => 'a1', 'state' => 'queued']]),
            $this->json(200, ['data' => ['id' => 'a1', 'state' => 'processing']]),
            $this->json(503, $this->error('pdf_artifact_not_ready'), ['Retry-After' => '2']),
            new Response(200, ['Content-Type' => 'application/pdf', 'ETag' => '"a1"', 'Content-Disposition' => 'attachment; filename="f.pdf"'], $pdf),
            new Response(304, ['ETag' => '"a1"']),
        );

        $prepared = RawRequest::send($sdk, 'POST', '/companies/{company}/pdf-artifacts', ['json' => [
            'document_type' => 'invoice', 'document_id' => '0199aaaa-0000-7000-8000-000000000001',
            'presentation_id' => '0199aaaa-0000-7000-8000-000000000002', 'purpose' => 'complete', 'format' => 'a4', 'variant' => 'official',
        ]], ['company' => self::COMPANY]);
        $state = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-artifacts/{artifact}', [], ['company' => self::COMPANY, 'artifact' => 'a1']);
        $pending = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-artifacts/{artifact}/download', ['headers' => ['Accept' => 'application/pdf']], ['company' => self::COMPANY, 'artifact' => 'a1']);
        $ready = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-artifacts/{artifact}/download', ['headers' => ['Accept' => 'application/pdf']], ['company' => self::COMPANY, 'artifact' => 'a1']);
        $cached = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-artifacts/{artifact}/download', ['headers' => ['Accept' => 'application/pdf', 'If-None-Match' => '"a1"']], ['company' => self::COMPANY, 'artifact' => 'a1']);

        $this->assertSame(202, $prepared->getStatusCode());
        $this->assertNotSame('', $this->request(0)->getHeaderLine('Idempotency-Key'));
        $this->assertSame('', $this->request(1)->getHeaderLine('Idempotency-Key'), 'polling does not prepare or render');
        $this->assertSame(503, $pending->getStatusCode());
        $this->assertSame(2, $this->meta($pending)->retryAfter);
        $this->assertSame(hash('sha256', $pdf), hash('sha256', (string) $ready->getBody()));
        $this->assertSame(304, $cached->getStatusCode());
        $this->assertSame('', (string) $cached->getBody());
        $this->assertCount(5, $this->history, 'no automatic retry of the 503');
    }

    // ---------------------------------------------------------------- CE21

    public function test_ce21_forget_is_parsed_by_the_generated_method_whatever_the_audit_id_type(): void
    {
        foreach ([19, '0199aaaa-0000-7000-8000-000000000001'] as $id) {
            $sdk = $this->sdk($this->json(200, ['data' => ['id' => $id, 'forgotten' => true]]));

            $response = $sdk->deliveryNotes->signatureAudits->publicApiV1DeliveryNotesSignatureAuditsForget(self::COMPANY, 'a1', 'key-9');

            $this->assertTrue($response->object?->data->forgotten);
            $this->assertSame('key-9', $this->request()->getHeaderLine('Idempotency-Key'));
        }
    }

    // ---------------------------------------------------------------- CE22

    public function test_ce22_send_428_409_and_410_raise_and_are_not_retried_and_the_raw_call_carries_the_preconditions(): void
    {
        foreach ([428 => 'pdf_presentation_precondition_required', 409 => 'pdf_legal_source_changed', 410 => 'pdf_signature_personal_data_forgotten'] as $status => $code) {
            $sdk = $this->sdk($this->json($status, $this->error($code)));
            try {
                $sdk->invoices->publicApiV1InvoicesSend(new \Factuarea\Sdk\Models\Operations\PublicApiV1InvoicesSendRequest(company: self::COMPANY, invoice: 'i1', idempotencyKey: 'key-5'));
                $this->fail("A $status must raise.");
            } catch (APIException $e) {
                $this->assertSame($status, $e->statusCode);
            } catch (ErrorThrowable $e) {
                $this->assertSame($code, $e->container->error->code);
            }
            $this->assertCount(1, $this->history);
        }

        $sdk = $this->sdk($this->json(200, ['data' => ['id' => 'i1']]));
        RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/{invoice}/send', [
            'headers' => ['Idempotency-Key' => 'key-5'],
            'json' => ['to' => 'cliente@example.com', 'expected_selection_version' => 4, 'expected_source_token' => 'tok', 'expected_preview_context_fingerprint' => 'fp'],
        ], ['company' => self::COMPANY, 'invoice' => 'i1']);
        $this->assertSame('tok', json_decode((string) $this->request()->getBody(), true)['expected_source_token']);
    }

    // ---------------------------------------------------------------- CE23

    public function test_ce23_bulk_pdf_and_quarterly_zip_expose_their_partial_result_headers_and_exact_bytes(): void
    {
        $zip = "PK\x03\x04\x00\xff zip";
        $sdk = $this->sdk(
            new Response(200, ['Content-Type' => 'application/zip', 'X-Bulk-Total' => '3', 'X-Bulk-Successful' => '2', 'X-Bulk-Failed' => '1'], $zip),
            new Response(200, ['Content-Type' => 'application/zip', 'X-Quarterly-Processed-Count' => '0', 'X-Quarterly-Total-Invoices' => '0', 'X-Quarterly-Year' => '2026', 'X-Quarterly-Quarter' => '3'], $zip),
        );

        $bulk = $sdk->invoices->publicApiV1InvoicesBulkPdf(new BulkPdfInvoicesV1Request(ids: ['i1', 'i2', 'i3']), self::COMPANY, 'key-6');
        $quarter = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlyDownloadZip(new QuarterlyDownloadV1Request(year: 2026, quarter: 3, includeIndex: false), self::COMPANY, 'key-7');

        $this->assertSame($zip, $bulk->bytes);
        $this->assertSame(['3'], $bulk->headers['X-Bulk-Total']);
        $this->assertSame(['1'], $bulk->headers['X-Bulk-Failed']);
        $this->assertSame(['0'], $quarter->headers['X-Quarterly-Processed-Count']);
        $this->assertSame('{"ids":["i1","i2","i3"]}', (string) $this->request(0)->getBody());
        $this->assertSame('{"year":2026,"quarter":3,"include_index":false}', (string) $this->request(1)->getBody());
    }

    public function test_ce23_include_non_invoiced_charges_present_false_omitted_and_null_need_the_raw_body(): void
    {
        $sdk = $this->sdk($this->json(200, []), $this->json(200, []), $this->json(200, []));
        $base = ['year' => 2026, 'quarter' => 3];

        foreach ([$base, $base + ['include_non_invoiced_charges' => false], $base + ['include_non_invoiced_charges' => null]] as $body) {
            RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/quarterly/download-zip', ['json' => $body], ['company' => self::COMPANY]);
        }

        $this->assertSame('{"year":2026,"quarter":3}', (string) $this->request(0)->getBody());
        $this->assertSame('{"year":2026,"quarter":3,"include_non_invoiced_charges":false}', (string) $this->request(1)->getBody());
        $this->assertSame('{"year":2026,"quarter":3,"include_non_invoiced_charges":null}', (string) $this->request(2)->getBody());
    }

    // ---------------------------------------------------------------- CE25

    /** @return array<string, array{0: int}> */
    public static function errorStatuses(): array
    {
        $cases = [];
        foreach ([401, 403, 404, 410, 422, 429, 500, 502, 503] as $status) {
            $cases["status $status"] = [$status];
        }

        return $cases;
    }

    #[DataProvider('errorStatuses')]
    public function test_ce25_every_error_status_raises_once_without_leaking_the_key_and_keeps_retry_after(int $status): void
    {
        $sdk = $this->sdk($this->json($status, $this->error('some_code'), ['Retry-After' => '3', 'X-Request-Id' => 'req-9']));

        try {
            $sdk->invoices->publicApiV1InvoicesShow(self::COMPANY, 'i1', options: NoRetry::options());
            $this->fail("A $status must raise.");
        } catch (APIException|ErrorThrowable $e) {
            $this->assertStringNotContainsString('fact_test_x', $e->getMessage());
            $raw = $e instanceof APIException ? $e->rawResponse : $e->container->rawResponse;
            $this->assertNotNull($raw);
            $this->assertSame($status, $raw->getStatusCode());
            $this->assertSame(3, ResponseMeta::fromResponse($raw)->retryAfter);
        }
        $this->assertCount(1, $this->history);
    }

    public function test_ce25_if_none_match_on_the_tax_catalog_returns_a_typed_304_and_reads_have_no_write_headers(): void
    {
        $sdk = $this->sdk(new Response(304, ['ETag' => '"cat-1"']));

        $response = RequestOverrides::run(fn () => $sdk->taxCatalog->publicApiV1TaxCatalogShow(), headers: ['If-None-Match' => '"cat-1"']);

        $this->assertSame(304, $response->statusCode);
        $this->assertSame('"cat-1"', $this->request()->getHeaderLine('If-None-Match'));
        $this->assertSame('', $this->request()->getHeaderLine('Idempotency-Key'));
        $this->assertSame('', $this->request()->getHeaderLine('If-Match'));
    }

    public function test_ce25_a_replay_with_a_different_body_is_a_422_the_caller_must_not_retry_with_a_new_key(): void
    {
        $sdk = $this->sdk($this->json(422, $this->error('idempotency_key_reused')));

        $response = RawRequest::send($sdk, 'POST', '/companies/{company}/pdf-templates', ['headers' => ['Idempotency-Key' => 'same'], 'json' => ['name' => 'otro']], ['company' => self::COMPANY]);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame('same', $this->request()->getHeaderLine('Idempotency-Key'));
        $this->assertCount(1, $this->history);
    }

    public function test_generated_enums_reject_unknown_values_but_extra_response_fields_are_ignored(): void
    {
        $extra = $this->face() + ['field_added_by_the_contract' => ['x' => 1]];
        $this->assertSame(200, $this->sdk($this->json(200, ['data' => $extra]))->faceSubmissions->publicApiV1FaceSubmissionsShow(self::COMPANY, 'f1')->statusCode);

        $this->expectException(PropertyMissingException::class);
        $this->sdk($this->json(200, ['data' => ['object' => 'a_new_object_type'] + $extra]))->faceSubmissions->publicApiV1FaceSubmissionsShow(self::COMPANY, 'f1');
    }
}
