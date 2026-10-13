# Native Service Insights and CSAT reads

`CrmClient::serviceInsights(RequiredPaths)` uses the same configured SDK transport, security, hooks, errors and GET retry policy as the existing client. Supply the actual native V1 paths relative to the configured V1 base:

- `crm/service-insights` — `service_insights.metrics`, CSAT cohort.
- `crm/service-insights/service-metrics` — `service_insights.service_metrics`, service analysis.
- `crm/service-insights/drill` — `service_insights.drill`, contributing Ticket IDs.

These protected routes require both `service_insights:read` and `customer_service:read`. Current Company, sandbox, credential, operation and field-level admission remain server responsibilities; the SDK does not fabricate permission flags or put authority into a query.

```php
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceFilters;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceMetricsRequest;

$insights = (new CrmClient($sdk))->serviceInsights(new RequiredPaths(
    'crm/service-insights',
    'crm/service-insights/service-metrics',
    'crm/service-insights/drill',
));
$analysis = $insights->getServiceMetricsForRequest(new ServiceMetricsRequest(
    start: '2026-10-24T00:00:00.123456Z',
    end: '2026-10-27T00:00:00.654321Z',
    clockMode: 'business',
    filters: new ServiceFilters(status: 'pending'),
))->body->data;
foreach ($insights->getServiceInsightDrillThroughItems($analysis, 'nrt', perPage: 25, maxPages: 100) as $ticket) {
    // Typed DrillItem: public ID, version, cycle and sample count; label/status can be masked null.
}
```

The original primitive `getServiceInsights`, `getServiceMetrics` and `getServiceInsightDrillThrough` methods are unchanged. The typed alternative adds the real closed GET filters: `agent_id`, `team_id` and `status`. `Omitted::Value` stays absent; explicit null is not accepted as a native GET filter. Response selection filters remain nullable facts, and drill queries omit those null response values. Historical User UUIDs and business Team UUIDv7s retain their distinct types. The cohort is half-open, at most 366 days, with exact UTC instants containing six fractional digits. Natural and business clocks are explicit; the client does not normalize offsets or invent a calendar.

The iterator uses the original `AnalysisMetrics` selection, `asOf`, `predicateHash` and `authorizationVersion`. Each GET passes the same original `as_of` and predicate and validates the returned C7, metric and requested page/size through the existing method. It then checks that the contributing total stays stable, that the page length agrees with the native array-slice pagination and that Ticket IDs do not repeat. A short nonfinal page, changed snapshot/C7/predicate, inconsistent size or changed count raises an error before yielding that page's IDs. It never requests a newer metrics head to repair an original snapshot.

Numbered pages are adapted locally to the existing `PageIterator`; no new opaque server cursor is invented. `firstPage` supports an explicit resume against the same original analysis. An explicit `maxPages` bound raises an overflow before an extra HTTP call. Already yielded items are a partial stream if a later page fails; they must not be presented as a complete cohort. Native 403/409/422/429 errors keep their request ID and Retry-After, and do not become an empty list or an automatic fresh query.

Zero observations remain zero counts with null mean/median; the SDK does not turn `n=0` into a zero rating or zero duration. Masked denominator/duration/rating facts remain null independently of an admitted eligible count. Duration decimals retain six fractional digits as strings, and CSAT values retain four; values are not cast through floating point. These three routes expose no financial Money or currency filter, so none is synthesized. Sending a survey, voting, exports, financial metrics and a package release are separate capabilities. This SDK read group does not activate them or claim an executed backend/sandbox integration.

The causal addition is covered by the new offline `Tests/Custom/Crm/ServiceInsightsBatch74` group. Previous ServiceInsights and Opportunities Source/tests are retained, and their already green cases were not rerun for this addition.
