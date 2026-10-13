<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\WhatsApp;
final class SendUnconfirmed extends \RuntimeException
{
    public function __construct(public readonly SendIntent $intent, \Throwable $previous, public readonly ?string $requestId = null)
    {
        parent::__construct('WhatsApp send is unconfirmed. Retain this intent and query its original; never resend automatically.', 0, $previous);
    }
}
