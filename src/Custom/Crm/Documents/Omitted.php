<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;

/** Explicitly omit an optional wire field; null remains a JSON null. */
enum Omitted
{
    case Value;
}
