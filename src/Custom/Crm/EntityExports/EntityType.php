<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

enum EntityType: string
{
    case Organization = 'organization';
    case Person = 'person';
    case Lead = 'lead';
    case Opportunity = 'opportunity';
    case Activity = 'activity';
    case ServiceTicket = 'service_ticket';
}
