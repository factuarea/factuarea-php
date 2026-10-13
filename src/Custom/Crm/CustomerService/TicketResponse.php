<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService;

use Psr\Http\Message\ResponseInterface;

/** @template T of object */
final readonly class TicketResponse
{
    /** @param T $data */
    public function __construct(public object $data, public ResponseInterface $rawResponse)
    {
    }
    public function statusCode(): int
    {
        return $this->rawResponse->getStatusCode();
    }
    /** @return array<string,list<string>> */
    public function headers(): array
    {
        return $this->rawResponse->getHeaders();
    }
}
