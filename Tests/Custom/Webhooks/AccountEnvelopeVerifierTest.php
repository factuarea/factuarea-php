<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Webhooks;

use Factuarea\Sdk\Custom\Webhooks\WebhookSignatureException;
use Factuarea\Sdk\Custom\Webhooks\WebhookVerifier;
use PHPUnit\Framework\TestCase;

/**
 * Account webhooks reuse the company signature scheme (`t=<unix>,v1=<hex>` over
 * `"<unix>.<raw_body>"`), so the same verifier covers the account envelope, which
 * adds `account`, `account_relationship` and `company_id` to the event.
 */
final class AccountEnvelopeVerifierTest extends TestCase
{
    private const SECRET = 'whsec_account_d2hzZWNfYWNjb3VudF9zZWNyZXQ';

    private const NOW = 1_700_000_000;

    private const ACCOUNT_ID = '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a00';

    private const COMPANY_ID = '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a02';

    /**
     * An `AccountEventEnvelope` as the contract defines it: every required field
     * (`id`, `type`, `api_version`, `created`, `livemode`, `test`, `correlation_id`,
     * `account`, `account_relationship`, `company_id`, `data`), with `account` as an
     * object and `type` a real `AccountEventType`.
     */
    private const PAYLOAD = '{"id":"01933b70-f2c3-7a15-b8d4-6e9a1b3c4e03","type":"account.nif_added","api_version":"2026-09-23","created":1700000000,"livemode":true,"test":false,"correlation_id":null,"account":{"id":"01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a00"},"account_relationship":"managed","company_id":"01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a02","data":{"type":"account.nif_added","object":{"object":"company","id":"01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a02"}}}';

    private function sign(string $payload, string $secret): string
    {
        return 't='.self::NOW.',v1='.hash_hmac('sha256', self::NOW.'.'.$payload, $secret);
    }

    public function test_an_account_envelope_signed_with_the_right_secret_keeps_its_account_fields(): void
    {
        $event = (new WebhookVerifier())->verify(
            self::PAYLOAD,
            $this->sign(self::PAYLOAD, self::SECRET),
            self::SECRET,
            300,
            self::NOW,
        );

        $this->assertSame('account.nif_added', $event['type']);
        $this->assertSame(self::ACCOUNT_ID, $event['account']['id']);
        $this->assertSame('managed', $event['account_relationship']);
        $this->assertSame(self::COMPANY_ID, $event['company_id']);
    }

    public function test_an_account_envelope_signed_with_another_secret_is_rejected(): void
    {
        $header = $this->sign(self::PAYLOAD, 'whsec_another_account_secret');

        $this->expectException(WebhookSignatureException::class);
        (new WebhookVerifier())->verify(self::PAYLOAD, $header, self::SECRET, 300, self::NOW);
    }
}
