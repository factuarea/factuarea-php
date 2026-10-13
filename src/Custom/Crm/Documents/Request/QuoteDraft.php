<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

/** Canonical prefiscal recipient. Totals, lines, authority and fiscal promotion are owner operations. */
final readonly class QuoteDraft
{
    public function __construct(public string $date, public string $recipientKind, public string|null|Omitted $recipientId = Omitted::Value,
        public string|null|Omitted $expiryDate = Omitted::Value, public string|null|Omitted $seriesId = Omitted::Value,
        public string|null|Omitted $clientId = Omitted::Value, public string|null|Omitted $notes = Omitted::Value,
        public string|null|Omitted $terms = Omitted::Value, public int|Omitted $expectedRootVersion = Omitted::Value,
        public int|Omitted $expectedLineVersion = Omitted::Value)
    {
        Wire::date($date);
        if (!in_array($recipientKind, ['unassigned', 'commercial_counterparty', 'business_contact'], true)) {
            throw new InvalidArgumentException('Unsupported quote recipient.');
        }
        if ($recipientKind === 'unassigned') {
            if (is_string($recipientId)) {
                throw new InvalidArgumentException('An unassigned recipient cannot have an id.');
            }
        } else {
            Wire::uuid($recipientId);
        }
        if (is_string($expiryDate) && (Wire::date($expiryDate) < $date)) {
            throw new InvalidArgumentException('Quote expiry precedes its date.');
        }
        foreach ([$seriesId, $clientId] as $id) {
            if (is_string($id)) {
                Wire::uuid($id, true);
            }
        }
        if (is_string($clientId) && $recipientKind !== 'business_contact') {
            throw new InvalidArgumentException('An existing customer requires a business-contact recipient.');
        }
        foreach ([$expectedRootVersion, $expectedLineVersion] as $version) {
            if (is_int($version)) {
                Wire::integer($version);
            }
        }
    }
    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $recipient = ['kind' => $this->recipientKind];
        if (!$this->recipientId instanceof Omitted) {
            $recipient['id'] = $this->recipientId;
        }
        $out = ['date' => $this->date, 'recipient' => $recipient];
        foreach (['expiry_date' => $this->expiryDate, 'series_id' => $this->seriesId, 'client_id' => $this->clientId, 'notes' => $this->notes, 'terms' => $this->terms, 'expected_root_version' => $this->expectedRootVersion, 'expected_line_version' => $this->expectedLineVersion] as $key => $value) {
            if (!$value instanceof Omitted) {
                $out[$key] = $value;
            }
        }

        return $out;
    }
}
