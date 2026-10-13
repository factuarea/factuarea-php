# Native Opportunity reads

The additive `CrmClient::opportunities()` resource uses the existing configured SDK HTTP client, authentication, hooks, profile/version headers and native error hierarchy. Its real V1 routes are `GET crm/opportunity` and `GET crm/opportunity/{opportunity}` relative to the configured V1 server. Their native operation IDs are `crm_opportunities.list` and `crm_opportunities.show`, and the server requires `crm_opportunities:read`. Knowing an ID, a definition version or a result never grants current access.

```php
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\Opportunities\Filters;
use Factuarea\Sdk\Custom\Crm\Opportunities\ListOpportunitiesRequest;

$opportunities = (new CrmClient($sdk))->opportunities();
$page = $opportunities->listOpportunities(new ListOpportunitiesRequest(
    filters: new Filters(status: 'open', sort: 'id', direction: 'asc'),
    limit: 25,
));
foreach ($opportunities->listOpportunityItems(
    new ListOpportunitiesRequest(filters: new Filters(status: 'open'), limit: 25),
    maxPages: 100,
) as $opportunity) {
    // Opportunity DTO; amount and weightedValue are exact decimal strings.
}
$detail = $opportunities->getOpportunity($knownOpportunityId)->body->data;
```

Configure `$sdk` using the existing native SDK builder/security source. An optional `withHeaders($xActiveProfile, $factuareaVersion)` creates an independent resource sharing that same configured transport. Only these two native override headers are accepted; the server selects the admitted Company and checks scopes, field masks and sandbox on every request. No Company, credential, authorization version or idempotency key is injected into these GET queries.

`OpportunityResult::data` is an `Opportunity` or the native `MissingOpportunity` variant `{opportunity:null}`. An Opportunity has required nullable fields: `null` can represent a current hidden field or a genuine nullable fact; the SDK does not manufacture a value or turn a missing field into null. Participants, versioned playbook answers, evidence IDs and history entries have separate typed models. Public wire keys remain `id` and `*_id`; PHP properties use camel case. Single-record amounts retain four decimals, probabilities two, dates `YYYY-MM-DD`, and timestamps their native offset. Authorized totals have separate currency models and can exceed the single-record amount ceiling.

`Filters` supports the actual V1 IDs, native date/source/phone filters, sort and direction. `TypedFilters` retains the supplied definition version and typed `FilterPredicate` fields/versions/operators, including omitted values versus explicit null or false. The server validates the current field definition, permissible operators, operands and field-level access. An omitted `Filters`, cursor or limit stays absent; an explicit empty `Filters` sends `{}`. The native server enforces the configured maximum page size; the SDK requires a positive explicit limit.

`listOpportunityItems()` adapts this owner's `next_cursor`-only envelope to the existing SDK `PageIterator`. It keeps each opaque cursor and the same filter JSON/limit, leaves the original request unchanged, rejects repeated cursors and throws when `maxPages` would truncate a list. A rejected later page propagates its native 403/409/422/429 error, request ID and Retry-After. Use individual page calls when you need to retain `nextCursor` for a later resume. Native current selection or authorization changes can invalidate a cursor; a previously read object is not an authorization cache.

This group contains read methods. Existing GET retry configuration is preserved; no mutation, effect receipt, new intent or automatic resend is added. It does not claim the entire CRM inventory, Lead→Opportunity→Activity journey, package release or an executed backend/sandbox integration. The backend Source contracts were inspected without starting the application or accessing a database.
