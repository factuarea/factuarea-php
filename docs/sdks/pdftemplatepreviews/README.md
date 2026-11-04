# PdfTemplatePreviews

## Overview

### Available Operations

* [publicApiV1PdfTemplatePreviewsCreate](#publicapiv1pdftemplatepreviewscreate) - Create a PDF template preview

## publicApiV1PdfTemplatePreviewsCreate

Render an illustrative or draft PDF to try a template before using it. `document_type` and `source` select what is previewed: `example` data, an `unsaved_form`, a `persisted_draft`, a `presentation_revision` or an `invoice_substitution`. It returns the metadata of a preview artifact; read and download it with the PDF artifact operations. A preview never changes a document, numbering or the retained presentation. It counts against the PDF generation budget, so a request without `Idempotency-Key` is rejected. Requires `pdfs:read` and `pdf_templates:read`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_template_previews.create" method="post" path="/companies/{company}/pdf-template-previews" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->pdfTemplatePreviews->publicApiV1PdfTemplatePreviewsCreate(
    company: '241235e2-1d60-4a42-8745-43cc722a2355',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\CreatePdfTemplatePreviewRequest12(
        documentType: Components\CreatePdfTemplatePreviewRequestDocumentType2::Quote,
        purpose: Components\CreatePdfTemplatePreviewRequestPurpose2::Corrective,
        format: Components\CreatePdfTemplatePreviewRequestFormat2::A4,
        locale: Components\CreatePdfTemplatePreviewRequestLocale2::Es,
        basePreset: Components\CreatePdfTemplatePreviewRequestBasePreset2::Classic,
        configuration: new Components\PdfTemplateConfiguration(
            branding: new Components\PdfTemplateConfigurationBranding(
                mode: Components\PdfTemplateConfigurationBrandingMode::Imago,
                size: Components\PdfTemplateConfigurationSize::Inherit,
            ),
            typography: new Components\PdfTemplateConfigurationTypography(
                fontFamily: Components\PdfTemplateConfigurationFontFamily::Calibri,
                fontSize: 3922.2,
            ),
            colors: new Components\PdfTemplateConfigurationColors(
                text: '<value>',
                line: '<value>',
                primary: '<value>',
                secondary: '<value>',
                tableHeaderBg: '<value>',
                tableBorder: '<value>',
                alternateRow: '<value>',
            ),
            page: new Components\PdfTemplateConfigurationPage(
                orientation: Components\PdfTemplateConfigurationOrientation::Portrait,
                marginsMm: new Components\PdfTemplateConfigurationMarginsMm(
                    top: 303797,
                    right: 308859,
                    bottom: 302020,
                    left: 505957,
                ),
                showPageNumbers: false,
            ),
            watermark: new Components\PdfTemplateConfigurationWatermark(
                enabled: true,
                text: '<value>',
            ),
            titles: new Components\PdfTemplateConfigurationTitles(
                invoice: new Components\PdfTemplateConfigurationInvoice(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                simplified: new Components\PdfTemplateConfigurationSimplified(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                corrective: new Components\PdfTemplateConfigurationCorrective(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                f3: new Components\PdfTemplateConfigurationF3(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                quote: new Components\PdfTemplateConfigurationQuote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                proforma: new Components\PdfTemplateConfigurationProforma(
                    es: null,
                    en: '<value>',
                    ca: '<value>',
                ),
                deliveryNote: new Components\PdfTemplateConfigurationDeliveryNote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
            ),
            columns: [
                new Components\PdfTemplateConfigurationColumn(
                    id: Components\PdfTemplateConfigurationId::TotalWithTax,
                    visible: false,
                    labels: new Components\PdfTemplateConfigurationLabels(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                ),
            ],
            companyFields: new Components\PdfTemplateConfigurationCompanyFields(
                name: true,
                taxId: false,
                address: false,
                email: false,
                phone: true,
                website: false,
            ),
            clientFields: new Components\PdfTemplateConfigurationClientFields(
                name: false,
                taxId: true,
                address: true,
                email: true,
                phone: true,
            ),
            customFieldDefinitions: [
                new Components\PdfTemplateConfigurationCustomFieldDefinition(
                    key: '<key>',
                    label: new Components\PdfTemplateConfigurationLabel(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                    visible: false,
                ),
            ],
            sections: new Components\PdfTemplateConfigurationSections(
                notesMode: Components\PdfTemplateConfigurationNotesMode::Auto,
                footerText: new Components\PdfTemplateConfigurationFooterText(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                conditionsText: new Components\PdfTemplateConfigurationConditionsText(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                commercialRegisterText: new Components\PdfTemplateConfigurationCommercialRegisterText(
                    es: '<value>',
                    en: null,
                    ca: '<value>',
                ),
                showPaymentInfo: false,
                showBankDetails: false,
            ),
        ),
        source: new Components\PdfPreviewUnsavedFormSource(
            form: new Components\PdfPreviewForm(
                seriesId: '4e0e1205-17a1-4e2a-8191-ac41fc8249b1',
                contactId: '22a6b3ca-51d4-4653-a935-e3a591d7f074',
                selection: new Components\Selection(
                    mode: Components\PdfPreviewFormMode::Inherit,
                    templateId: '19c26eaa-8146-4a8f-98a5-a0a6ae14c70a',
                ),
                document: new Components\PdfPreviewFormDocument(
                    date: LocalDate::parse('2025-07-01'),
                    dueDate: LocalDate::parse('2026-05-07'),
                    expiryDate: null,
                    deliveryDate: null,
                    number: '<value>',
                    notes: '<value>',
                    terms: '<value>',
                    paymentMethod: '<value>',
                    paymentReference: '<value>',
                    operationDate: LocalDate::parse('2024-04-16'),
                    operationRegime: '<value>',
                    legalType: '<value>',
                    isCorrective: false,
                    correctionNature: '<value>',
                    correctionType: '<value>',
                    correctionReason: '<value>',
                    originalInvoiceId: '2d57fdc0-145d-45be-880c-3b4a5eb2e7c3',
                    originalInvoiceNumber: '<value>',
                    originalInvoiceDate: LocalDate::parse('2026-07-06'),
                    baseRectificada: '<value>',
                    cuotaRectificada: '<value>',
                    recargoRectificado: '<value>',
                    substitutedInvoices: [],
                    shippingCost: '<value>',
                ),
                recipient: new Components\Recipient(
                    name: '<value>',
                    taxId: '<id>',
                    address: '473 Ratke Club',
                    postalCode: '42498',
                    city: 'Sandy',
                    province: '<value>',
                    country: 'Guinea',
                    email: 'Itzel.Lowe@hotmail.com',
                    phone: '(838) 571-2197 x8354',
                    website: '<value>',
                    displayName: 'Marques.Oberbrunner39',
                ),
                delivery: new Components\Delivery(
                    address: '95259 Hans Brooks',
                    postalCode: '02280',
                    city: 'East Jess',
                    province: '<value>',
                    country: 'Haiti',
                    transportDetails: '<value>',
                ),
                lines: [
                    new Components\PdfPreviewFormLine(
                        rowKey: '<value>',
                        description: 'for unfurl the when rationalize commercial at',
                        additionalDescription: '<value>',
                        notes: '<value>',
                        lineType: Components\PdfPreviewFormLineType::Suplido,
                        unit: null,
                        quantity: '<value>',
                        unitPrice: '<value>',
                        discount: '<value>',
                        vatRate: '<value>',
                        retentionRate: '<value>',
                        surchargeRate: '<value>',
                        indirectTaxRegime: '<value>',
                        regimeKey: null,
                        exemptionReason: '<value>',
                        exemptionReasonText: '<value>',
                        businessReferences: new Components\LineBusinessReferences(
                            issuerContractReference: '<value>',
                            issuerContractDate: LocalDate::parse('2024-09-19'),
                            issuerTransactionReference: '<value>',
                            issuerTransactionDate: null,
                            receiverContractReference: '<value>',
                            receiverContractDate: LocalDate::parse('2026-04-30'),
                            receiverTransactionReference: '<value>',
                            receiverTransactionDate: LocalDate::parse('2026-07-08'),
                            fileReference: '<value>',
                            fileDate: LocalDate::parse('2024-01-03'),
                        ),
                        deliveryNotesReferences: [],
                        sequenceNumber: 388892,
                        commercialDisplay: new Components\CommercialDisplay(
                            sku: '<value>',
                            unitCode: '<value>',
                            unitLabel: '<value>',
                            variantLabel: '<value>',
                            packQuantity: '<value>',
                            unitsPerPack: '<value>',
                            commercialWeight: '<value>',
                            photoAssetId: '6a9cab82-f1c2-4ba1-8117-daee708b1972',
                            batchNumber: '<value>',
                            expiryDate: LocalDate::parse('2026-05-14'),
                            commercialWeightUnit: '<value>',
                            presentationLabel: '<value>',
                            priceUnitCode: '<value>',
                            optionSnapshots: [
                                new Components\OptionSnapshots(
                                    groupName: '<value>',
                                    valueName: '<value>',
                                    priceAdjustment: '<value>',
                                    applied: true,
                                ),
                            ],
                            optionAdjustmentsAbsorbed: true,
                            isPack: false,
                            totalUnits: '<value>',
                        ),
                    ),
                ],
                businessData: new Components\PdfPreviewFormBusinessData(
                    businessReferences: new Components\BusinessDataBusinessReferences(
                        receiverContractReference: '<value>',
                        receiverTransactionReference: '<value>',
                        fileReference: '<value>',
                    ),
                    invoicingPeriod: new Components\InvoicingPeriod(
                        startDate: LocalDate::parse('2024-09-25'),
                        endDate: LocalDate::parse('2025-01-11'),
                    ),
                    legalLiterals: [],
                ),
                administrativeCentres: [],
                paymentTerms: [
                    new Components\PaymentTerms(
                        dueDate: LocalDate::parse('2025-11-18'),
                        amount: '818.93',
                        paymentMeans: '<value>',
                        account: null,
                    ),
                ],
                customFields: [
                    new Components\PdfPreviewFormCustomField(
                        field: '<value>',
                        value: '<value>',
                    ),
                ],
                legalMentions: [
                    '<value 1>',
                    '<value 2>',
                    '<value 3>',
                ],
            ),
        ),
    ),
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: prepared

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_template_previews.create" method="post" path="/companies/{company}/pdf-template-previews" example="prepared" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->pdfTemplatePreviews->publicApiV1PdfTemplatePreviewsCreate(
    company: 'ae187dc8-5ad1-415b-bbab-2300c12001ff',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\CreatePdfTemplatePreviewRequestA4(
        locale: Components\CreatePdfTemplatePreviewRequestLocale7::Ca,
        source: new Components\PdfPreviewInvoiceSubstitutionSource(
            documentId: 'fe7084ab-a152-4b7b-9b5a-a03f23e98a8d',
            sourceInvoiceUuids: [],
            lineUuids: [],
            clientUuid: '99997671-5caf-488e-8fb5-0798f6aac90e',
            notes: '<value>',
            preparedDate: LocalDate::parse('2026-03-05'),
            templateId: '36e2f154-39a2-4484-ae5c-49dc997f9bf1',
        ),
    ),
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: prepared_persisted_draft

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_template_previews.create" method="post" path="/companies/{company}/pdf-template-previews" example="prepared_persisted_draft" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->pdfTemplatePreviews->publicApiV1PdfTemplatePreviewsCreate(
    company: 'a11c7a2b-8953-44b3-ae64-b8c1937ea00e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\CreatePdfTemplatePreviewRequest13(
        documentType: Components\CreatePdfTemplatePreviewRequestDocumentType3::DeliveryNote,
        purpose: Components\CreatePdfTemplatePreviewRequestPurpose3::Complete,
        format: Components\CreatePdfTemplatePreviewRequestFormat3::Ticket80,
        locale: Components\CreatePdfTemplatePreviewRequestLocale3::Ca,
        basePreset: Components\CreatePdfTemplatePreviewRequestBasePreset3::Classic,
        configuration: new Components\PdfTemplateConfiguration(
            branding: new Components\PdfTemplateConfigurationBranding(
                mode: Components\PdfTemplateConfigurationBrandingMode::Imago,
                size: Components\PdfTemplateConfigurationSize::Inherit,
            ),
            typography: new Components\PdfTemplateConfigurationTypography(
                fontFamily: Components\PdfTemplateConfigurationFontFamily::Calibri,
                fontSize: 3922.2,
            ),
            colors: new Components\PdfTemplateConfigurationColors(
                text: '<value>',
                line: '<value>',
                primary: '<value>',
                secondary: '<value>',
                tableHeaderBg: '<value>',
                tableBorder: '<value>',
                alternateRow: '<value>',
            ),
            page: new Components\PdfTemplateConfigurationPage(
                orientation: Components\PdfTemplateConfigurationOrientation::Portrait,
                marginsMm: new Components\PdfTemplateConfigurationMarginsMm(
                    top: 303797,
                    right: 308859,
                    bottom: 302020,
                    left: 505957,
                ),
                showPageNumbers: false,
            ),
            watermark: new Components\PdfTemplateConfigurationWatermark(
                enabled: true,
                text: '<value>',
            ),
            titles: new Components\PdfTemplateConfigurationTitles(
                invoice: new Components\PdfTemplateConfigurationInvoice(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                simplified: new Components\PdfTemplateConfigurationSimplified(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                corrective: new Components\PdfTemplateConfigurationCorrective(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                f3: new Components\PdfTemplateConfigurationF3(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                quote: new Components\PdfTemplateConfigurationQuote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                proforma: new Components\PdfTemplateConfigurationProforma(
                    es: null,
                    en: '<value>',
                    ca: '<value>',
                ),
                deliveryNote: new Components\PdfTemplateConfigurationDeliveryNote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
            ),
            columns: [
                new Components\PdfTemplateConfigurationColumn(
                    id: Components\PdfTemplateConfigurationId::TotalWithTax,
                    visible: false,
                    labels: new Components\PdfTemplateConfigurationLabels(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                ),
            ],
            companyFields: new Components\PdfTemplateConfigurationCompanyFields(
                name: true,
                taxId: false,
                address: false,
                email: false,
                phone: true,
                website: false,
            ),
            clientFields: new Components\PdfTemplateConfigurationClientFields(
                name: false,
                taxId: true,
                address: true,
                email: true,
                phone: true,
            ),
            customFieldDefinitions: [
                new Components\PdfTemplateConfigurationCustomFieldDefinition(
                    key: '<key>',
                    label: new Components\PdfTemplateConfigurationLabel(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                    visible: false,
                ),
            ],
            sections: new Components\PdfTemplateConfigurationSections(
                notesMode: Components\PdfTemplateConfigurationNotesMode::Auto,
                footerText: new Components\PdfTemplateConfigurationFooterText(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                conditionsText: new Components\PdfTemplateConfigurationConditionsText(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                commercialRegisterText: new Components\PdfTemplateConfigurationCommercialRegisterText(
                    es: '<value>',
                    en: null,
                    ca: '<value>',
                ),
                showPaymentInfo: false,
                showBankDetails: false,
            ),
        ),
        source: new Components\PdfPreviewPersistedDraftSource(
            documentId: '<id>',
        ),
    ),
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: ready

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_template_previews.create" method="post" path="/companies/{company}/pdf-template-previews" example="ready" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->pdfTemplatePreviews->publicApiV1PdfTemplatePreviewsCreate(
    company: 'af32bd74-5751-4881-ab26-26749e028599',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\CreatePdfTemplatePreviewRequest13(
        documentType: Components\CreatePdfTemplatePreviewRequestDocumentType3::DeliveryNote,
        purpose: Components\CreatePdfTemplatePreviewRequestPurpose3::Complete,
        format: Components\CreatePdfTemplatePreviewRequestFormat3::Ticket80,
        locale: Components\CreatePdfTemplatePreviewRequestLocale3::Ca,
        basePreset: Components\CreatePdfTemplatePreviewRequestBasePreset3::Classic,
        configuration: new Components\PdfTemplateConfiguration(
            branding: new Components\PdfTemplateConfigurationBranding(
                mode: Components\PdfTemplateConfigurationBrandingMode::Imago,
                size: Components\PdfTemplateConfigurationSize::Inherit,
            ),
            typography: new Components\PdfTemplateConfigurationTypography(
                fontFamily: Components\PdfTemplateConfigurationFontFamily::Calibri,
                fontSize: 3922.2,
            ),
            colors: new Components\PdfTemplateConfigurationColors(
                text: '<value>',
                line: '<value>',
                primary: '<value>',
                secondary: '<value>',
                tableHeaderBg: '<value>',
                tableBorder: '<value>',
                alternateRow: '<value>',
            ),
            page: new Components\PdfTemplateConfigurationPage(
                orientation: Components\PdfTemplateConfigurationOrientation::Portrait,
                marginsMm: new Components\PdfTemplateConfigurationMarginsMm(
                    top: 303797,
                    right: 308859,
                    bottom: 302020,
                    left: 505957,
                ),
                showPageNumbers: false,
            ),
            watermark: new Components\PdfTemplateConfigurationWatermark(
                enabled: true,
                text: '<value>',
            ),
            titles: new Components\PdfTemplateConfigurationTitles(
                invoice: new Components\PdfTemplateConfigurationInvoice(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                simplified: new Components\PdfTemplateConfigurationSimplified(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                corrective: new Components\PdfTemplateConfigurationCorrective(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                f3: new Components\PdfTemplateConfigurationF3(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                quote: new Components\PdfTemplateConfigurationQuote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                proforma: new Components\PdfTemplateConfigurationProforma(
                    es: null,
                    en: '<value>',
                    ca: '<value>',
                ),
                deliveryNote: new Components\PdfTemplateConfigurationDeliveryNote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
            ),
            columns: [
                new Components\PdfTemplateConfigurationColumn(
                    id: Components\PdfTemplateConfigurationId::TotalWithTax,
                    visible: false,
                    labels: new Components\PdfTemplateConfigurationLabels(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                ),
            ],
            companyFields: new Components\PdfTemplateConfigurationCompanyFields(
                name: true,
                taxId: false,
                address: false,
                email: false,
                phone: true,
                website: false,
            ),
            clientFields: new Components\PdfTemplateConfigurationClientFields(
                name: false,
                taxId: true,
                address: true,
                email: true,
                phone: true,
            ),
            customFieldDefinitions: [
                new Components\PdfTemplateConfigurationCustomFieldDefinition(
                    key: '<key>',
                    label: new Components\PdfTemplateConfigurationLabel(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                    visible: false,
                ),
            ],
            sections: new Components\PdfTemplateConfigurationSections(
                notesMode: Components\PdfTemplateConfigurationNotesMode::Auto,
                footerText: new Components\PdfTemplateConfigurationFooterText(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                conditionsText: new Components\PdfTemplateConfigurationConditionsText(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                commercialRegisterText: new Components\PdfTemplateConfigurationCommercialRegisterText(
                    es: '<value>',
                    en: null,
                    ca: '<value>',
                ),
                showPaymentInfo: false,
                showBankDetails: false,
            ),
        ),
        source: new Components\PdfPreviewPersistedDraftSource(
            documentId: '<id>',
        ),
    ),
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_template_previews.create" method="post" path="/companies/{company}/pdf-template-previews" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->pdfTemplatePreviews->publicApiV1PdfTemplatePreviewsCreate(
    company: '6aa7863f-f585-4cd4-a0d1-53aad9182867',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\CreatePdfTemplatePreviewRequest1(
        documentType: Components\CreatePdfTemplatePreviewRequestDocumentType1::Invoice,
        purpose: Components\CreatePdfTemplatePreviewRequestPurpose1::Simplified,
        format: Components\CreatePdfTemplatePreviewRequestFormat1::Ticket58,
        locale: Components\CreatePdfTemplatePreviewRequestLocale1::Ca,
        basePreset: Components\CreatePdfTemplatePreviewRequestBasePreset1::Minimal,
        configuration: new Components\PdfTemplateConfiguration(
            branding: new Components\PdfTemplateConfigurationBranding(
                mode: Components\PdfTemplateConfigurationBrandingMode::Imago,
                size: Components\PdfTemplateConfigurationSize::Inherit,
            ),
            typography: new Components\PdfTemplateConfigurationTypography(
                fontFamily: Components\PdfTemplateConfigurationFontFamily::Calibri,
                fontSize: 3922.2,
            ),
            colors: new Components\PdfTemplateConfigurationColors(
                text: '<value>',
                line: '<value>',
                primary: '<value>',
                secondary: '<value>',
                tableHeaderBg: '<value>',
                tableBorder: '<value>',
                alternateRow: '<value>',
            ),
            page: new Components\PdfTemplateConfigurationPage(
                orientation: Components\PdfTemplateConfigurationOrientation::Portrait,
                marginsMm: new Components\PdfTemplateConfigurationMarginsMm(
                    top: 303797,
                    right: 308859,
                    bottom: 302020,
                    left: 505957,
                ),
                showPageNumbers: false,
            ),
            watermark: new Components\PdfTemplateConfigurationWatermark(
                enabled: true,
                text: '<value>',
            ),
            titles: new Components\PdfTemplateConfigurationTitles(
                invoice: new Components\PdfTemplateConfigurationInvoice(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                simplified: new Components\PdfTemplateConfigurationSimplified(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                corrective: new Components\PdfTemplateConfigurationCorrective(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                f3: new Components\PdfTemplateConfigurationF3(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                quote: new Components\PdfTemplateConfigurationQuote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                proforma: new Components\PdfTemplateConfigurationProforma(
                    es: null,
                    en: '<value>',
                    ca: '<value>',
                ),
                deliveryNote: new Components\PdfTemplateConfigurationDeliveryNote(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
            ),
            columns: [
                new Components\PdfTemplateConfigurationColumn(
                    id: Components\PdfTemplateConfigurationId::TotalWithTax,
                    visible: false,
                    labels: new Components\PdfTemplateConfigurationLabels(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                ),
            ],
            companyFields: new Components\PdfTemplateConfigurationCompanyFields(
                name: true,
                taxId: false,
                address: false,
                email: false,
                phone: true,
                website: false,
            ),
            clientFields: new Components\PdfTemplateConfigurationClientFields(
                name: false,
                taxId: true,
                address: true,
                email: true,
                phone: true,
            ),
            customFieldDefinitions: [
                new Components\PdfTemplateConfigurationCustomFieldDefinition(
                    key: '<key>',
                    label: new Components\PdfTemplateConfigurationLabel(
                        es: '<value>',
                        en: '<value>',
                        ca: '<value>',
                    ),
                    visible: false,
                ),
            ],
            sections: new Components\PdfTemplateConfigurationSections(
                notesMode: Components\PdfTemplateConfigurationNotesMode::Auto,
                footerText: new Components\PdfTemplateConfigurationFooterText(
                    es: '<value>',
                    en: '<value>',
                    ca: '<value>',
                ),
                conditionsText: new Components\PdfTemplateConfigurationConditionsText(
                    es: '<value>',
                    en: '<value>',
                    ca: null,
                ),
                commercialRegisterText: new Components\PdfTemplateConfigurationCommercialRegisterText(
                    es: '<value>',
                    en: null,
                    ca: '<value>',
                ),
                showPaymentInfo: false,
                showBankDetails: false,
            ),
        ),
        source: new Components\PdfPreviewExampleSource(
            scenario: Components\Scenario::SimplifiedCorrective,
        ),
    ),
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_template_previews.create" method="post" path="/companies/{company}/pdf-template-previews" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->pdfTemplatePreviews->publicApiV1PdfTemplatePreviewsCreate(
    company: '71619921-a2e1-416a-806d-4a5d6d3073e5',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\CreatePdfTemplatePreviewRequest2(
        documentType: Components\CreatePdfTemplatePreviewRequestDocumentType6::DeliveryNote,
        purpose: Components\CreatePdfTemplatePreviewRequestPurpose6::Complete,
        format: Components\CreatePdfTemplatePreviewRequestFormat6::A4,
        locale: Components\CreatePdfTemplatePreviewRequestLocale6::Ca,
        source: new Components\PdfPreviewPresentationRevisionSource(
            documentId: '<id>',
            parentId: '<id>',
            templateId: '<id>',
            sourcePolicy: Components\PdfPreviewPresentationRevisionSourceSourcePolicy::Captured,
            expectedVersion: 347246,
        ),
    ),
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\CreatePdfTemplatePreviewRequest1\|Components\CreatePdfTemplatePreviewRequest12\|Components\CreatePdfTemplatePreviewRequest13\|Components\CreatePdfTemplatePreviewRequest2\|Components\CreatePdfTemplatePreviewRequest14\|Components\CreatePdfTemplatePreviewRequestA4](../../Models/Components/CreatePdfTemplatePreviewRequestUnion.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Internal reversible preparation/rendering uses the existing idempotency policy; the same key and raw body replay, another body with that key is rejected with `422 idempotency_key_reused` before admission.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1PdfTemplatePreviewsCreateResponse](../../Models/Operations/PublicApiV1PdfTemplatePreviewsCreateResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 410, 422, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |