<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService;

/** Closed native writes. Workspace WIP/approval moves use a separate contract. */
enum TicketOperation: string
{
    case MoveToStage = 'customer_service.move_to_stage';
    case AttachDocument = 'customer_service.attach_document';
    case DetachDocument = 'customer_service.detach_document';

    public function suffix(): string
    {
        return match ($this) {
            self::MoveToStage => '/move-to-stage',
            self::AttachDocument => '/attachments',
            self::DetachDocument => '/attachments/remove',
        };
    }
}
