<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

use Factuarea\Sdk\Factuarea;

/** Additive CRM entry point sharing the configured canonical SDK instance. */
final readonly class CrmClient
{
    public ContactPeople $contactPeople;

    public Leads $leads;

    public Pipelines $pipelines;

    public function __construct(Factuarea $sdk)
    {
        $transport = new Transport($sdk->sdkConfiguration);
        $this->contactPeople = new ContactPeople($transport);
        $this->leads = new Leads($transport);
        $this->pipelines = new Pipelines($transport);
    }
}
