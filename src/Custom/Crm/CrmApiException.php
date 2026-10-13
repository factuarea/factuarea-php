<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

use Factuarea\Sdk\Models\Errors\ErrorThrowable;

/**
 * Sanitized native CRM failure facts. The generated error envelope, response
 * body, human message and any transport exception are intentionally not kept.
 */
class CrmApiException extends \RuntimeException
{
    public readonly ?int $statusCode;

    public readonly string $machineCode;

    public readonly ?string $subcode;

    public readonly ?string $param;

    public readonly ?string $requestId;

    public readonly ?string $retryAfter;

    public readonly bool $unconfirmed;

    public function __construct(?int $statusCode, string $code, ?string $subcode = null,
        ?string $param = null, ?string $requestId = null, ?string $retryAfter = null, bool $unconfirmed = false)
    {
        $this->statusCode = $statusCode !== null && $statusCode >= 100 && $statusCode <= 599 ? $statusCode : null;
        $this->machineCode = self::safe($code, '/^[a-z][a-z0-9_]{0,99}$/D') ?? 'native_rejection';
        $this->subcode = self::safe($subcode, '/^[a-z][a-z0-9_]{0,99}$/D');
        $this->param = self::safe($param, '/^[A-Za-z][A-Za-z0-9_.-]{0,127}$/D');
        $this->requestId = self::safe($requestId, '/^[A-Za-z0-9_.:-]{1,255}$/D');
        $this->retryAfter = self::safeRetryAfter($retryAfter);
        $this->unconfirmed = $unconfirmed;

        parent::__construct('CRM API request failed: '.$this->machineCode.'.', $this->statusCode ?? 0);
    }

    /** Adapt the generated envelope while retaining only bounded machine facts. */
    public static function fromErrorThrowable(ErrorThrowable $failure, bool $write = false): self
    {
        $response = $failure->container->rawResponse;
        $status = $response?->getStatusCode();
        $error = $failure->container->error;
        $code = self::safe($error->code, '/^[a-z][a-z0-9_]{0,99}$/D') ?? 'native_rejection';
        $subcode = self::safe($error->subcode, '/^[a-z][a-z0-9_]{0,99}$/D');
        $unconfirmed = $write && (
            ($status !== null && $status >= 500)
            || str_contains($code, 'unconfirmed')
            || str_contains($subcode ?? '', 'unconfirmed')
        );
        $requestId = self::safe($response?->getHeaderLine('X-Request-Id'), '/^[A-Za-z0-9_.:-]{1,255}$/D')
            ?? self::safe($error->requestId, '/^[A-Za-z0-9_.:-]{1,255}$/D');
        $retryAfter = self::safeRetryAfter($response?->getHeaderLine('Retry-After'));
        /** @var class-string<CrmApiException> $class */
        $class = match (true) {
            $status === 422 => CrmValidationException::class,
            in_array($status, [401, 403, 404], true) => CrmPermissionException::class,
            $status === 429 => CrmRateLimitException::class,
            $unconfirmed => CrmUnconfirmedException::class,
            $status === 409 => CrmConflictException::class,
            default => self::class,
        };

        return new $class($status, $code, $subcode, $error->param, $requestId, $retryAfter, $unconfirmed);
    }

    /** A transport failure has no response identity unless the caller has one. */
    public static function unconfirmedTransport(?string $requestId = null): CrmUnconfirmedException
    {
        return new CrmUnconfirmedException(null, 'transport_failure', requestId: $requestId, unconfirmed: true);
    }

    private static function safe(?string $value, string $pattern): ?string
    {
        return $value !== null && preg_match($pattern, $value) === 1 ? $value : null;
    }

    /** Keep only the two standard Retry-After forms; arbitrary header text may contain secrets. */
    private static function safeRetryAfter(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        if (preg_match('/^[0-9]{1,10}$/D', $value) === 1) {
            return $value;
        }
        if (preg_match('/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), (0[1-9]|[12][0-9]|3[01]) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) [0-9]{4} ([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9] GMT$/D', $value) !== 1) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat('D, d M Y H:i:s \\G\\M\\T', $value, new \DateTimeZone('UTC'));
        $errors = \DateTimeImmutable::getLastErrors();
        if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
            || $date->format('D, d M Y H:i:s \\G\\M\\T') !== $value) {
            return null;
        }

        return $value;
    }
}
