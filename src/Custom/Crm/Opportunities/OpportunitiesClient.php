<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\Schema;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Pagination\PageIterator;
use GuzzleHttp\Psr7\Response;

/** Native authenticated V1 reads using the SAME configured SDK transport, security, hooks and errors. */
final readonly class OpportunitiesClient
{
    /** @param array<string,string> $headers */
    public function __construct(private Transport $transport, private array $headers = [])
    {
        foreach ($headers as $name => $value) {
            if (! in_array($name, ['X-Active-Profile', 'Factuarea-Version'], true)) {
                throw new \InvalidArgumentException('Only native profile/version overrides are supported.');
            }
            Validation::header($value);
        }
    }

    public function withHeaders(?string $xActiveProfile = null, ?string $factuareaVersion = null): self
    {
        $headers = $this->headers;
        foreach (['X-Active-Profile' => $xActiveProfile, 'Factuarea-Version' => $factuareaVersion] as $name => $value) {
            if ($value !== null) {
                Validation::header($value);
                $headers[$name] = $value;
            }
        }

        return new self($this->transport, $headers);
    }

    /** @return CrmResponse<OpportunityPageResult> */
    public function listOpportunities(ListOpportunitiesRequest $request): CrmResponse
    {
        $response = $this->transport->request('GET', 'crm/opportunity', 'crm_opportunities.list',
            OpportunityPageResult::class, $request->query(), null, null, false, $this->headers);
        if ($response->statusCode() !== 200) {
            throw new \UnexpectedValueException('Native Opportunity list requires HTTP 200.');
        }

        return $response;
    }

    /** Native absence is data:{opportunity:null}; it is never converted into an empty Opportunity.
     * @return CrmResponse<OpportunityResult> */
    public function getOpportunity(string $opportunityId): CrmResponse
    {
        Schema::validate(Schemas::UUID, $opportunityId);
        $response = $this->transport->request('GET', 'crm/opportunity/'.rawurlencode($opportunityId), 'crm_opportunities.show',
            OpportunityResult::class, [], null, null, false, $this->headers);
        if ($response->statusCode() !== 200 || ($response->body->data instanceof Opportunity
            && $response->body->data->id !== $opportunityId)) {
            throw new \UnexpectedValueException('Native Opportunity detail has an unexpected status or target identity.');
        }

        return $response;
    }

    /** Streams complete native cursor pages without modifying the original request or treating an error as end-of-page.
     * @return \Generator<int,Opportunity> */
    public function listOpportunityItems(ListOpportunitiesRequest $request, int $maxPages = 1000): \Generator
    {
        if ($maxPages < 1) {
            throw new \InvalidArgumentException('maxPages must be positive.');
        }
        $pages = 0;
        $seenCursors = $request->cursor === Omitted::Value ? [] : [$request->cursor => true];
        /** @var list<Opportunity> $items */
        $items = [];
        $iterator = new PageIterator(function (?string $cursor) use ($request, $maxPages, &$pages, &$seenCursors, &$items): Response {
            if (++$pages > $maxPages) {
                throw new \OverflowException('Opportunity pagination reached maxPages; resume with the last native cursor.');
            }
            $page = $this->listOpportunities($cursor === null ? $request : $request->withCursor($cursor))->body->data;
            $items = $page->items;
            if ($page->nextCursor !== null) {
                Validation::cursor($page->nextCursor);
                if (isset($seenCursors[$page->nextCursor])) {
                    throw new \UnexpectedValueException('Native Opportunity pagination repeated its opaque cursor.');
                }
                $seenCursors[$page->nextCursor] = true;
            }

            return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => array_keys($items), 'has_more' => $page->nextCursor !== null, 'next_cursor' => $page->nextCursor,
            ], JSON_THROW_ON_ERROR));
        });
        foreach ($iterator->items() as $index) {
            if (! is_int($index) || ! isset($items[$index])) {
                throw new \UnexpectedValueException('Native Opportunity pagination returned an invalid item index.');
            }
            yield $items[$index];
        }
    }
}
