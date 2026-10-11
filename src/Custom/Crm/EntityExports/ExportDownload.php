<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/** Same ready native binary; no decoding, decimal coercion or SDK CSV generation. */
final readonly class ExportDownload
{
    public function __construct(public ExportJob $job, public ResponseInterface $rawResponse)
    {
        if ($job->state() !== ExportStatus::Ready || $rawResponse->getStatusCode() !== 200) {
            throw new \UnexpectedValueException('A download requires its factual ready original and native successful response.');
        }
    }

    public function stream(): StreamInterface
    {
        return $this->rawResponse->getBody();
    }
}
