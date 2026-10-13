<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\CrmConflictException;
use Factuarea\Sdk\Custom\Crm\CrmPermissionException;
use Factuarea\Sdk\Custom\Crm\CrmRateLimitException;
use Factuarea\Sdk\Custom\Crm\CrmUnconfirmedException;
use Factuarea\Sdk\Custom\Crm\CrmValidationException;
use Factuarea\Sdk\Models\Components\Error2;
use Factuarea\Sdk\Models\Components\ErrorType;
use Factuarea\Sdk\Models\Errors\Error;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CrmApiExceptionTest extends TestCase
{
    /** @return iterable<string, array{int, string, class-string<CrmApiException>}> */
    public static function nativeFailures(): iterable
    {
        yield 'validation' => [422, 'parameter_invalid', CrmValidationException::class];
        yield 'conflict' => [409, 'crm_version_conflict', CrmConflictException::class];
        yield 'forbidden' => [403, 'insufficient_scope', CrmPermissionException::class];
        yield 'rate limit' => [429, 'rate_limit_exceeded', CrmRateLimitException::class];
    }

    #[DataProvider('nativeFailures')]
    public function test_native_failure_exposes_only_bounded_machine_facts(
        int $status,
        string $code,
        string $expected,
    ): void
    {
        $native = $this->native(
            $status,
            $code,
            'server message contains private token',
            'req_body_123',
            'expected_version',
        );
        $failure = CrmApiException::fromErrorThrowable($native);

        self::assertInstanceOf($expected, $failure);
        self::assertSame($status, $failure->statusCode);
        self::assertSame($code, $failure->machineCode);
        self::assertSame('expected_version', $failure->param);
        self::assertSame('req_header_123', $failure->requestId);
        self::assertSame('7', $failure->retryAfter);
        self::assertFalse($failure->unconfirmed);
        self::assertNull($failure->getPrevious());
        self::assertStringNotContainsString('private token', $failure->getMessage());
        self::assertStringNotContainsString('fact_live_secret', (string) $failure);
    }

    public function test_unconfirmed_conflict_is_typed_without_retaining_native_payload(): void
    {
        $failure = CrmApiException::fromErrorThrowable(
            $this->native(409, 'crm_effect_unconfirmed', 'private server text', 'req_effect_123'),
            write: true,
        );

        self::assertInstanceOf(CrmUnconfirmedException::class, $failure);
        self::assertSame(409, $failure->statusCode);
        self::assertSame('req_header_123', $failure->requestId);
        self::assertTrue($failure->unconfirmed);
        self::assertNull($failure->getPrevious());
        self::assertObjectNotHasProperty('rawResponse', $failure);
    }

    public function test_transport_uncertainty_has_no_cause_or_secret_fields(): void
    {
        $failure = CrmApiException::unconfirmedTransport('req_transport_123');

        self::assertInstanceOf(CrmUnconfirmedException::class, $failure);
        self::assertNull($failure->statusCode);
        self::assertSame('transport_failure', $failure->machineCode);
        self::assertSame('req_transport_123', $failure->requestId);
        self::assertTrue($failure->unconfirmed);
        self::assertNull($failure->getPrevious());
        self::assertObjectNotHasProperty('idempotencyKey', $failure);
    }

    private function native(
        int $status,
        string $code,
        string $message,
        ?string $requestId,
        ?string $param = null,
    ): ErrorThrowable {
        $error = new Error(
            new Error2(ErrorType::ApiError, $code, $message, param: $param, requestId: $requestId),
            new Response($status, ['X-Request-Id' => 'req_header_123', 'Retry-After' => '7'], 'fact_live_secret'),
        );

        return $error->toException();
    }
}
