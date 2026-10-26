<?php

/**
 * Document templates, presentation and PDF statuses with the Factuarea PHP SDK.
 *
 * The pinned generated SDK predates the document-template operations, so this
 * example uses the three hand-written helpers of `src/Custom` for what the
 * generated methods cannot express (see docs/REGENERATION.md):
 *
 * - RawRequest      calls an operation that has no typed method yet.
 * - RequestOverrides adds headers (If-Match, If-None-Match) and sends explicit
 *                    JSON nulls on a generated method.
 * - ResponseMeta    reads status, ETag, Retry-After and the JSON body of any
 *                    response, including a 2xx the generated method rejects.
 * - NoRetry         stops the automatic 429/5xx retries (FACe).
 *
 * Every company operation lives under `/v1/companies/{company}/…`, so each
 * function takes the company `id` (UUID v7, `data.scope[].id` of `GET /v1/me`):
 * the raw calls fill the `{company}` path parameter with it and the generated
 * methods take it as their `company` argument. Never pass a tax ID or a name.
 *
 * No data here is real: identifiers are placeholders. Run it against a
 * `fact_test_` key only; nothing is executed when the file is included.
 *
 *   FACTUAREA_API_KEY=fact_test_... php examples/document-templates.php
 */

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Request\NoRetry;
use Factuarea\Sdk\Custom\Request\RawRequest;
use Factuarea\Sdk\Custom\Request\RequestOverrides;
use Factuarea\Sdk\Custom\Response\ResponseMeta;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\UpdateSeriesRequest;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Operations\PublicApiV1InvoicesPdfRequest;
use Factuarea\Sdk\Models\Operations\PublicApiV1SeriesUpdateRequest;

/**
 * Lists the templates and clears the description of one of them, with the ETag
 * of the read as the precondition. `null` means "clear"; omitting a field keeps it.
 *
 * @return array<string, mixed>
 */
function updateTemplate(Factuarea $sdk, string $companyId, string $templateId): array
{
    $current = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-templates/{template}', [], ['company' => $companyId, 'template' => $templateId]);
    $meta = ResponseMeta::fromResponse($current);
    if ($meta->etag === null) {
        throw new RuntimeException('The read carried no ETag.');
    }

    $updated = RawRequest::send($sdk, 'PATCH', '/companies/{company}/pdf-templates/{template}', [
        'headers' => ['If-Match' => $meta->etag],
        'json' => ['name' => 'Factura corporativa', 'description' => null],
    ], ['company' => $companyId, 'template' => $templateId]);

    return match (ResponseMeta::fromResponse($updated)->statusCode) {
        200 => (array) ResponseMeta::fromResponse($updated)->json(),
        412, 428 => throw new RuntimeException('Read the template again and retry with its new ETag.'),
        409 => throw new RuntimeException('Another change won: reread, merge and retry.'),
        default => throw new RuntimeException('Unexpected status '.$updated->getStatusCode()),
    };
}

/**
 * Series: the generated PATCH keeps working, with If-Match and an explicit null to
 * unassign the template (the typed request model cannot carry the field).
 */
function unassignSeriesTemplate(Factuarea $sdk, string $companyId, string $seriesId, string $etag): void
{
    RequestOverrides::run(
        fn () => $sdk->series->publicApiV1SeriesUpdate(new PublicApiV1SeriesUpdateRequest(
            company: $companyId,
            series: $seriesId,
            body: new UpdateSeriesRequest(name: 'B2B'),
        )),
        clear: ['pdf_template_id'],
        headers: ['If-Match' => $etag],
    );
}

/**
 * Issues an invoice when the company has the revisioned presentation active: the
 * preconditions of the preview travel in the body, which the generated `issue`
 * method has no parameter for. The same key repeats the same call safely.
 */
function issueWithPreconditions(Factuarea $sdk, string $companyId, string $invoiceId, string $idempotencyKey, string $sourceToken, string $fingerprint, int $selectionVersion): bool
{
    $response = RawRequest::send($sdk, 'POST', '/companies/{company}/invoices/{invoice}/issue', [
        'headers' => ['Idempotency-Key' => $idempotencyKey],
        'json' => [
            'expected_selection_version' => $selectionVersion,
            'expected_source_token' => $sourceToken,
            'expected_preview_context_fingerprint' => $fingerprint,
        ],
    ], ['company' => $companyId, 'invoice' => $invoiceId]);

    // 428: a precondition is missing; 409: the source changed since the preview.
    return $response->getStatusCode() === 200;
}

/**
 * Downloads an invoice PDF: 200 bytes, 304 when the cached copy is current, or
 * a 202 JSON body while the PDF renders (the generated method raises for it).
 *
 * @return array{status: int, bytes: ?string, etag: ?string, retryAfter: ?int}
 */
function downloadInvoicePdf(Factuarea $sdk, string $companyId, string $invoiceId, ?string $cachedEtag = null): array
{
    try {
        $call = fn () => $sdk->invoices->publicApiV1InvoicesPdf(new PublicApiV1InvoicesPdfRequest(company: $companyId, invoice: $invoiceId));
        $response = $cachedEtag === null ? $call() : RequestOverrides::run($call, headers: ['If-None-Match' => $cachedEtag]);
        $meta = ResponseMeta::fromResponse($response->rawResponse);

        return ['status' => $response->statusCode, 'bytes' => $response->bytes, 'etag' => $meta->etag ?? $cachedEtag, 'retryAfter' => null];
    } catch (APIException $e) {
        $meta = ResponseMeta::fromException($e);
        if ($meta->statusCode === 202) {
            return ['status' => 202, 'bytes' => null, 'etag' => null, 'retryAfter' => $meta->retryAfter];
        }

        throw $e; // 409 legal source changed, 410 erased, 422, 5xx...
    }
}

/**
 * FACe: one call, never a blind retry. A 200 is the replay of an accepted
 * submission (the generated method raises for it); a 502 or a 409 with an
 * attempt means the outcome is uncertain: reconcile with a GET, do not resend.
 */
function submitToFace(Factuarea $sdk, string $companyId, string $invoiceId, string $idempotencyKey): string
{
    try {
        $created = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(
            company: $companyId,
            invoice: $invoiceId,
            idempotencyKey: $idempotencyKey,
            options: NoRetry::options(),
        );

        return (string) $created->object?->data->status;
    } catch (APIException $e) {
        $meta = ResponseMeta::fromException($e);
        if ($meta->statusCode === 200) {
            return (string) ($meta->json()['data']['status'] ?? 'unknown');
        }

        throw $e;
    }
}

/**
 * Prepares a PDF artifact and waits for it, bounded: the download answers 503
 * with Retry-After until the artifact is ready.
 */
function waitForArtifact(Factuarea $sdk, string $companyId, string $artifactId, int $maxAttempts = 12): ?string
{
    for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
        $response = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-artifacts/{artifact}/download', ['headers' => ['Accept' => 'application/pdf']], ['company' => $companyId, 'artifact' => $artifactId]);
        if ($response->getStatusCode() === 200) {
            return (string) $response->getBody();
        }
        if ($response->getStatusCode() !== 503) {
            throw new RuntimeException('The artifact cannot be downloaded: HTTP '.$response->getStatusCode());
        }
        sleep(max(1, ResponseMeta::fromResponse($response)->retryAfter ?? 1));
    }

    return null;
}

if (PHP_SAPI === 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $apiKey = getenv('FACTUAREA_API_KEY');
    if (! is_string($apiKey) || ! str_starts_with($apiKey, 'fact_test_')) {
        fwrite(STDERR, "Set FACTUAREA_API_KEY to a fact_test_ key.\n");
        exit(1);
    }

    $sdk = FactuareaClient::create($apiKey);

    // The first company the key reaches; pick yours from `data.scope[]` in a real integration.
    $companyId = $sdk->account->publicApiV1AccountShow()->object?->data->scope[0]->id ?? null;
    if ($companyId === null) {
        fwrite(STDERR, "The key reaches no company.\n");
        exit(1);
    }

    $list = RawRequest::send($sdk, 'GET', '/companies/{company}/pdf-templates', ['query' => ['page' => 1, 'per_page' => 25]], ['company' => $companyId]);
    echo 'Templates: HTTP '.$list->getStatusCode().PHP_EOL;
}
