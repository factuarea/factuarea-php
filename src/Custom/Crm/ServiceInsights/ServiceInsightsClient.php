<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Schema;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Pagination\PageIterator;
use GuzzleHttp\Psr7\Response;

/** Read-only resource sharing native SDK authentication, errors, hooks and configured GET retries. */
final readonly class ServiceInsightsClient
{
    public function __construct(private Transport $transport, private RequiredPaths $paths)
    {
    }

    /** @return CrmResponse<CsatResult> */
    public function getServiceInsights(string $start, string $end): CrmResponse
    {
        ReadInput::cohort($start, $end);
        $response = $this->transport->request('GET', $this->paths->getServiceInsights, 'service_insights.metrics', CsatResult::class,
            ['start' => $start, 'end' => $end], null, null, false);
        if ($response->body->data->cohortStart !== $start || $response->body->data->cohortEnd !== $end) {
            throw new \UnexpectedValueException('The native CSAT result belongs to a different cohort.');
        }

        return $response;
    }

    /** @param array<string,string> $filters Public agent_id/team_id/status; omitted filters remain omitted.
     * @return CrmResponse<AnalysisResult> */
    public function getServiceMetrics(string $start, string $end, string $clockMode, array $filters = []): CrmResponse
    {
        $query = ReadInput::selection($start, $end, $clockMode, $filters);
        $response = $this->transport->request('GET', $this->paths->getServiceMetrics, 'service_insights.service_metrics',
            AnalysisResult::class, $query, null, null, false);
        $expected = ['start' => $start, 'end' => $end, 'clock_mode' => $clockMode,
            'agent_id' => $filters['agent_id'] ?? null, 'team_id' => $filters['team_id'] ?? null, 'status' => $filters['status'] ?? null];
        if ($response->body->data->selection->toArray() !== $expected) {
            throw new \UnexpectedValueException('The native metrics result belongs to a different selection.');
        }

        return $response;
    }

    /** Reuses the admitted original selection/as_of/predicate; never requests a latest head or invents a cursor.
     * @return CrmResponse<DrillResult> */
    public function getServiceInsightDrillThrough(AnalysisMetrics $original, string $metric, int $page = 1, int $perPage = 25): CrmResponse
    {
        $original->validate();
        Schema::validate(ReadInput::METRIC, $metric);
        Schema::validate(ReadInput::DRILL['properties']['page'], $page);
        Schema::validate(ReadInput::DRILL['properties']['per_page'], $perPage);
        $selection = $original->selection;
        $filters = [];
        foreach (['agent_id' => $selection->agentId, 'team_id' => $selection->teamId, 'status' => $selection->status] as $key => $value) {
            if ($value !== null) {
                $filters[$key] = $value;
            }
        }
        $query = ReadInput::selection($selection->start, $selection->end, $selection->clockMode, $filters);
        $response = $this->transport->request('GET', $this->paths->getServiceInsightDrillThrough, 'service_insights.drill',
            DrillResult::class, [...$query, 'as_of' => $original->asOf, 'predicate_hash' => $original->predicateHash,
                'metric' => $metric, 'page' => $page, 'per_page' => $perPage], null, null, false);
        $result = $response->body->data;
        if ($result->asOf !== $original->asOf || $result->predicateHash !== $original->predicateHash
            || $result->authorizationVersion !== $original->authorizationVersion || $result->metric !== $metric
            || $result->page !== $page || $result->perPage !== $perPage) {
            throw new \UnexpectedValueException('The native drill result does not retain the original selection and authorization.');
        }

        return $response;
    }

    /** Typed alternative to the existing primitive method, preserving its native selection validation.
     * @return CrmResponse<AnalysisResult> */
    public function getServiceMetricsForRequest(ServiceMetricsRequest $request): CrmResponse
    {
        return $this->getServiceMetrics($request->start, $request->end, $request->clockMode, $request->filters->query());
    }

    /** Streams the native contributing IDs for the SAME original as_of/predicate/C7 and selection.
     * @return \Generator<int,DrillItem> */
    public function getServiceInsightDrillThroughItems(AnalysisMetrics $original, string $metric,
        int $firstPage = 1, int $perPage = 25, int $maxPages = 1000): \Generator
    {
        $original->validate();
        Schema::validate(ReadInput::METRIC, $metric);
        Schema::validate(ReadInput::DRILL['properties']['page'], $firstPage);
        Schema::validate(ReadInput::DRILL['properties']['per_page'], $perPage);
        if ($maxPages < 1) {
            throw new \InvalidArgumentException('maxPages must be positive.');
        }
        $fetched = 0;
        $expectedPage = $firstPage;
        $originalTotal = null;
        /** @var array<string,true> $seenIds */
        $seenIds = [];
        /** @var list<DrillItem> $items */
        $items = [];
        $iterator = new PageIterator(function (?string $cursor) use ($original, $metric, $firstPage, $perPage, $maxPages,
            &$fetched, &$expectedPage, &$originalTotal, &$seenIds, &$items): Response {
            if (++$fetched > $maxPages) {
                throw new \OverflowException('Service Insights pagination reached maxPages; resume with the next native page.');
            }
            if ($cursor !== null && (string) $expectedPage !== $cursor) {
                throw new \UnexpectedValueException('Native Service Insights pagination is not sequential.');
            }
            $pageNumber = $cursor === null ? $firstPage : $expectedPage;
            $result = $this->getServiceInsightDrillThrough($original, $metric, $pageNumber, $perPage)->body->data;
            if ($originalTotal === null) {
                $originalTotal = $result->total;
            } elseif ($result->total !== $originalTotal) {
                throw new \UnexpectedValueException('Native Service Insights contributor count changed within the original snapshot.');
            }
            $offset = ($pageNumber - 1) * $perPage;
            $remaining = max(0, $result->total - $offset);
            if (count($result->items) !== min($perPage, $remaining)) {
                throw new \UnexpectedValueException('Native Service Insights page length contradicts its total and pagination.');
            }
            $items = $result->items;
            foreach ($items as $item) {
                if (isset($seenIds[$item->id])) {
                    throw new \UnexpectedValueException('Native Service Insights drill repeated a contributing Ticket identity.');
                }
                $seenIds[$item->id] = true;
            }
            $hasMore = $remaining > count($items);
            $expectedPage = $pageNumber + 1;

            return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => array_keys($items), 'has_more' => $hasMore, 'next_cursor' => $hasMore ? (string) $expectedPage : null,
            ], JSON_THROW_ON_ERROR));
        });
        foreach ($iterator->items() as $index) {
            if (! is_int($index) || ! isset($items[$index])) {
                throw new \UnexpectedValueException('Native Service Insights page returned an invalid item index.');
            }
            yield $items[$index];
        }
    }
}
