<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Operations;

use Psr\Http\Message\ResponseInterface;

final readonly class ListServiceCalendarsResponse
{
    /** @param array<string,list<string>> $headers */
    public function __construct(
        public string $contentType,
        public int $statusCode,
        public ResponseInterface $rawResponse,
        public ListServiceCalendarsResponseBody $object,
        public array $headers = [],
    ) {
    }
}
