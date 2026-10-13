<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

final class InvoicePreview extends WireModel
{
    public const SCHEMA = Schemas::INVOICE_PREVIEW;
    public const FIELDS = ['contact' => ['property' => 'contact', 'type' => ['model' => PreviewContact::class]], 'lines' => ['property' => 'lines', 'type' => ['items' => ['model' => PreviewLine::class]]], 'total_seconds' => ['property' => 'totalSeconds', 'type' => ['scalar' => 'int']], 'total_hours' => ['property' => 'totalHours', 'type' => ['scalar' => 'string']], 'time_entry_ids' => ['property' => 'timeEntryIds', 'type' => ['items' => ['scalar' => 'string']]], 'warnings' => ['property' => 'warnings', 'type' => ['items' => ['scalar' => 'string']]], 'totals' => ['property' => 'totals', 'type' => ['model' => PreviewTotals::class]]];

    /**
     * @param  list<PreviewLine>  $lines
     * @param  list<string>  $timeEntryIds
     * @param  list<string>  $warnings
     */
    public function __construct(public PreviewContact $contact, public array $lines, public int $totalSeconds, public string $totalHours, public array $timeEntryIds, public array $warnings, public PreviewTotals|null|Omitted $totals = Omitted::Value)
    {
        $this->validate();
    }
}
