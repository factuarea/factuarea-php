<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PreviewData extends WireModel
{
    public const SCHEMA = Schemas::PREVIEW;
    public const FIELDS = ['ticket_id' => ['property' => 'ticketId', 'type' => ['scalar' => 'string']], 'ticket_version' => ['property' => 'ticketVersion', 'type' => ['scalar' => 'int']], 'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']], 'preview' => ['property' => 'preview', 'type' => ['model' => InvoicePreview::class]]];

    public function __construct(public string $ticketId, public int $ticketVersion, public string $authorizationVersion, public InvoicePreview $preview)
    {
        $this->validate();
    }
}
