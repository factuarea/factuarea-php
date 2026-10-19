<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

/** Missing PATCH fields preserve their values; null is an explicit value. */
enum Omitted
{
    case Value;
}
