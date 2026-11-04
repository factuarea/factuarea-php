<?php

/**
 * Document templates, presentation and PDF statuses with the Factuarea PHP SDK.
 *
 * Every operation has a typed method. This example combines them with the
 * hand-written helpers of `src/Custom` for what the generated models cannot
 * express (see docs/REGENERATION.md):
 *
 * - RawRequest      calls any operation with a plain JSON body: an explicit
 *                    `null` in a template, or an operation newer than this release.
 * - RequestOverrides adds headers (If-Match) and sends explicit JSON nulls on a
 *                    generated method.
 * - ResponseMeta    reads status, ETag, Retry-After and the JSON body of any
 *                    response, or of a status the generated method does not declare.
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
use Factuarea\Sdk\Custom\Request\RawRequest;
use Factuarea\Sdk\Custom\Request\RequestOverrides;
use Factuarea\Sdk\Custom\Response\ResponseMeta;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\IssueInvoiceRequest;
use Factuarea\Sdk\Models\Components\UpdateSeriesRequest;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\Models\Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequest;
use Factuarea\Sdk\Models\Operations\PublicApiV1InvoicesIssueRequest;
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
 * preconditions of the preview travel in the typed body. The same key repeats
 * the same call safely.
 */
function issueWithPreconditions(Factuarea $sdk, string $companyId, string $invoiceId, string $idempotencyKey, string $sourceToken, string $fingerprint, int $selectionVersion): bool
{
    try {
        $sdk->invoices->publicApiV1InvoicesIssue(new PublicApiV1InvoicesIssueRequest(
            company: $companyId,
            invoice: $invoiceId,
            idempotencyKey: $idempotencyKey,
            body: new IssueInvoiceRequest(
                expectedSelectionVersion: $selectionVersion,
                expectedSourceToken: $sourceToken,
                expectedPreviewContextFingerprint: $fingerprint,
            ),
        ));

        return true;
    } catch (ErrorThrowable $e) {
        // 428: a precondition is missing; 409: the source changed since the preview.
        if (in_array($e->container->rawResponse?->getStatusCode(), [409, 428], true)) {
            return false;
        }

        throw $e;
    }
}

/**
 * Downloads an invoice PDF: 200 bytes, 304 when the cached copy is current, or
 * a 202 JSON body while the PDF renders, with the seconds to wait.
 *
 * @return array{status: int, bytes: ?string, etag: ?string, retryAfter: ?int}
 */
function downloadInvoicePdf(Factuarea $sdk, string $companyId, string $invoiceId, ?string $cachedEtag = null): array
{
    // 409 legal source changed, 410 erased, 422 and 5xx raise ErrorThrowable.
    $response = $sdk->invoices->publicApiV1InvoicesPdf(new PublicApiV1InvoicesPdfRequest(company: $companyId, invoice: $invoiceId, ifNoneMatch: $cachedEtag));
    $meta = ResponseMeta::fromResponse($response->rawResponse);

    if ($response->statusCode === 202) {
        return ['status' => 202, 'bytes' => null, 'etag' => null, 'retryAfter' => $meta->retryAfter ?? $response->object?->data->pdfOutputState->retryAfter];
    }

    return ['status' => $response->statusCode, 'bytes' => $response->bytes, 'etag' => $meta->etag ?? $cachedEtag, 'retryAfter' => null];
}

/**
 * FACe: one call, never a blind retry (the generated method does not retry it).
 * A 201 is a new submission and a 200 the replay of an accepted one; a 502 or a
 * 409 with an attempt means the outcome is uncertain: reconcile with a GET, do
 * not resend.
 */
function submitToFace(Factuarea $sdk, string $companyId, string $invoiceId, string $idempotencyKey): string
{
    $response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(new PublicApiV1InvoicesFaceSubmissionsSubmitRequest(
        company: $companyId,
        invoice: $invoiceId,
        idempotencyKey: $idempotencyKey,
    ));

    $submission = $response->twoHundredAndOneApplicationJsonObject->data ?? $response->twoHundredApplicationJsonObject?->data;

    return $submission?->status->value ?? 'unknown';
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
