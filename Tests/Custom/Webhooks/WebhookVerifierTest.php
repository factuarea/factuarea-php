<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Webhooks;

use Factuarea\Sdk\Custom\Webhooks\WebhookSignatureException;
use Factuarea\Sdk\Custom\Webhooks\WebhookVerifier;
use PHPUnit\Framework\TestCase;

final class WebhookVerifierTest extends TestCase
{
    private const SECRET = 'whsec_test_d2hzZWNfdGVzdF9zZWNyZXQ';

    private const PREVIOUS_SECRET = 'whsec_old_b2xkX3NlY3JldF92YWx1ZQ';

    private WebhookVerifier $verifier;

    protected function setUp(): void
    {
        $this->verifier = new WebhookVerifier();
    }

    /**
     * Build a `Factuarea-Signature` header exactly the way the backend's
     * HmacSignatureGenerator does: HMAC-SHA256 over `"<timestamp>.<payload>"`.
     *
     * @param  list<string>  $secrets  One secret => single v1; two => grace-window dual sign.
     */
    private function sign(string $payload, int $timestamp, array $secrets): string
    {
        $signedPayload = $timestamp.'.'.$payload;
        $parts = ['t='.$timestamp];
        foreach ($secrets as $secret) {
            $parts[] = 'v1='.hash_hmac('sha256', $signedPayload, $secret);
        }

        return implode(',', $parts);
    }

    public function test_verify_returns_decoded_event_for_a_valid_signature(): void
    {
        $payload = '{"type":"invoice.created","data":{"id":"inv_123"}}';
        $now = 1_700_000_000;
        $header = $this->sign($payload, $now, [self::SECRET]);

        $event = $this->verifier->verify($payload, $header, self::SECRET, 300, $now);

        $this->assertSame('invoice.created', $event['type']);
        $this->assertSame('inv_123', $event['data']['id']);
    }

    public function test_assert_signature_passes_silently_for_a_valid_signature(): void
    {
        $payload = '{"type":"client.updated"}';
        $now = 1_700_000_000;
        $header = $this->sign($payload, $now, [self::SECRET]);

        $this->verifier->assertSignature($payload, $header, self::SECRET, 300, $now);

        $this->expectNotToPerformAssertions();
    }

    public function test_rejects_a_signature_made_with_the_wrong_secret(): void
    {
        $payload = '{"type":"invoice.paid"}';
        $now = 1_700_000_000;
        $header = $this->sign($payload, $now, ['whsec_attacker_controlled_secret']);

        $this->expectException(WebhookSignatureException::class);
        $this->verifier->verify($payload, $header, self::SECRET, 300, $now);
    }

    public function test_rejects_a_tampered_payload(): void
    {
        $payload = '{"type":"invoice.paid","amount":100}';
        $now = 1_700_000_000;
        $header = $this->sign($payload, $now, [self::SECRET]);

        $tampered = '{"type":"invoice.paid","amount":9999}';

        $this->expectException(WebhookSignatureException::class);
        $this->verifier->verify($tampered, $header, self::SECRET, 300, $now);
    }

    public function test_rejects_a_timestamp_older_than_the_tolerance(): void
    {
        $payload = '{"type":"invoice.created"}';
        $signedAt = 1_700_000_000;
        $header = $this->sign($payload, $signedAt, [self::SECRET]);

        $now = $signedAt + 301; // 1 second past the 300s tolerance

        $this->expectException(WebhookSignatureException::class);
        $this->expectExceptionMessage('tolerance');
        $this->verifier->verify($payload, $header, self::SECRET, 300, $now);
    }

    public function test_rejects_a_timestamp_too_far_in_the_future(): void
    {
        $payload = '{"type":"invoice.created"}';
        $signedAt = 1_700_000_000;
        $header = $this->sign($payload, $signedAt, [self::SECRET]);

        $now = $signedAt - 301;

        $this->expectException(WebhookSignatureException::class);
        $this->verifier->verify($payload, $header, self::SECRET, 300, $now);
    }

    public function test_accepts_a_timestamp_at_the_edge_of_the_tolerance(): void
    {
        $payload = '{"type":"invoice.created"}';
        $signedAt = 1_700_000_000;
        $header = $this->sign($payload, $signedAt, [self::SECRET]);

        $now = $signedAt + 300; // exactly at the boundary

        $event = $this->verifier->verify($payload, $header, self::SECRET, 300, $now);
        $this->assertSame('invoice.created', $event['type']);
    }

    public function test_verifies_during_the_rotation_grace_window_with_the_new_secret(): void
    {
        $payload = '{"type":"invoice.created"}';
        $now = 1_700_000_000;
        // Delivery dual-signed with current + previous secret (24h grace window).
        $header = $this->sign($payload, $now, [self::SECRET, self::PREVIOUS_SECRET]);

        // Endpoint configured with the NEW secret still verifies.
        $event = $this->verifier->verify($payload, $header, self::SECRET, 300, $now);
        $this->assertSame('invoice.created', $event['type']);
    }

    public function test_verifies_during_the_rotation_grace_window_with_the_old_secret(): void
    {
        $payload = '{"type":"invoice.created"}';
        $now = 1_700_000_000;
        $header = $this->sign($payload, $now, [self::SECRET, self::PREVIOUS_SECRET]);

        // Endpoint still on the OLD secret also verifies the same delivery.
        $event = $this->verifier->verify($payload, $header, self::PREVIOUS_SECRET, 300, $now);
        $this->assertSame('invoice.created', $event['type']);
    }

    public function test_rejects_an_empty_header(): void
    {
        $this->expectException(WebhookSignatureException::class);
        $this->expectExceptionMessage('Missing');
        $this->verifier->verify('{}', '   ', self::SECRET, 300, 1_700_000_000);
    }

    public function test_rejects_a_header_without_a_timestamp(): void
    {
        $payload = '{}';
        $sig = hash_hmac('sha256', '1700000000.'.$payload, self::SECRET);

        $this->expectException(WebhookSignatureException::class);
        $this->verifier->verify($payload, 'v1='.$sig, self::SECRET, 300, 1_700_000_000);
    }

    public function test_rejects_a_header_without_any_v1_signature(): void
    {
        $this->expectException(WebhookSignatureException::class);
        $this->verifier->verify('{}', 't=1700000000', self::SECRET, 300, 1_700_000_000);
    }

    public function test_rejects_a_non_numeric_timestamp(): void
    {
        $this->expectException(WebhookSignatureException::class);
        $this->verifier->verify('{}', 't=not_a_number,v1=abc', self::SECRET, 300, 1_700_000_000);
    }

    public function test_no_exception_message_ever_contains_the_secret(): void
    {
        $payload = '{"type":"invoice.created"}';
        $header = $this->sign($payload, 1_700_000_000, ['whsec_wrong']);

        try {
            $this->verifier->verify($payload, $header, self::SECRET, 300, 1_700_000_000);
            $this->fail('Expected WebhookSignatureException.');
        } catch (WebhookSignatureException $e) {
            $this->assertStringNotContainsString(self::SECRET, $e->getMessage());
            $this->assertStringNotContainsString(self::PREVIOUS_SECRET, $e->getMessage());
        }
    }
}
