<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

final class ReceiptResultData extends WireModel
{
    public const SCHEMA = Schemas::RECEIPT_RESULT_DATA;
    public const FIELDS = ['binding' => ['property' => 'binding', 'type' => ['model' => Binding::class]], 'task_version' => ['property' => 'taskVersion', 'type' => ['scalar' => 'int']]];

    public function __construct(public Binding $binding, public int|Omitted $taskVersion = Omitted::Value)
    {
        $this->validate();
    }
}
