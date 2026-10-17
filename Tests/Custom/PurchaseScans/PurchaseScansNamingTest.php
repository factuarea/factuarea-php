<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\PurchaseScans;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Locks the public surface of the three generated classes the scanner adds
 * to the `purchaseScans`/`purchaseScanEmails`/`purchaseInvoices` groups
 * against `backend/docs/api/sdk-method-naming.md @ 1.1.0` (2026-09-23), as
 * measured and frozen in `anchors/contract.md` §"Tabla de nombres congelada"
 * of `scanner-sdk-cli-spec-sync`. Every operation method name below is the
 * one the naming document ALREADY publishes — not a hypothesis, unlike the
 * request/response class names in the sibling upload/source tests.
 *
 * Requires task 4.6's regeneration to run (the classes do not exist yet).
 */
final class PurchaseScansNamingTest extends TestCase
{
    /**
     * @return list<string> public, non-inherited, non-helper method names
     */
    private function operationMethods(string $class): array
    {
        $reflection = new ReflectionClass($class);

        $methods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isStatic() || $method->isConstructor()) {
                continue;
            }
            // `getUrl` is a helper present on every generated group class
            // (see e.g. Gallery::getUrl, PurchaseInvoices::getUrl), not an
            // API operation.
            if ($method->getName() === 'getUrl') {
                continue;
            }
            $methods[] = $method->getName();
        }

        sort($methods);

        return $methods;
    }

    public function test_purchase_scans_exposes_exactly_the_eleven_frozen_operations(): void
    {
        $expected = [
            'publicApiV1PurchaseScansArchive',
            'publicApiV1PurchaseScansConvert',
            'publicApiV1PurchaseScansCreate',
            'publicApiV1PurchaseScansDuplicateResolution',
            'publicApiV1PurchaseScansList',
            'publicApiV1PurchaseScansRestore',
            'publicApiV1PurchaseScansRetry',
            'publicApiV1PurchaseScansReview',
            'publicApiV1PurchaseScansShow',
            'publicApiV1PurchaseScansSource',
            'publicApiV1PurchaseScansStats',
        ];
        sort($expected);

        $this->assertSame($expected, $this->operationMethods(\Factuarea\Sdk\PurchaseScans::class));
    }

    public function test_purchase_scan_emails_exposes_only_list(): void
    {
        $this->assertSame(
            ['publicApiV1PurchaseScanEmailsList'],
            $this->operationMethods(\Factuarea\Sdk\PurchaseScanEmails::class),
        );
    }

    public function test_purchase_invoices_exposes_expense_categories(): void
    {
        $this->assertContains(
            'publicApiV1PurchaseInvoicesExpenseCategories',
            $this->operationMethods(\Factuarea\Sdk\PurchaseInvoices::class),
        );
    }
}
