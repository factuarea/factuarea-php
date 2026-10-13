# Native Conversation reads

`Factuarea\Sdk\Custom\Crm\Conversations` implements the two actual V1 read contracts: `GET /v1/crm/conversation` (`listCrmConversations`) and `GET /v1/crm/conversation/{conversation}` (`getCrmConversation`). The client uses the existing SDK transport, configured server, authentication, hooks and GET retry policy. It creates no UUID, effect, idempotency key or recovery mutation.

```php
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\Conversations\Filters;
use Factuarea\Sdk\Custom\Crm\Conversations\GetConversationRequest;
use Factuarea\Sdk\Custom\Crm\Conversations\ListConversationsRequest;

$conversations = (new CrmClient($sdk))->conversations();
$selection = new ListConversationsRequest(
    filters: new Filters(state: 'active', sort: 'last_message_at:desc'),
    limit: 25,
);
$first = $conversations->listConversations($selection);
foreach ($conversations->listConversationItems($selection) as $conversation) {
    // Public id and version are actual server values; subject and ownerId may be null.
}

// Use an id returned by a current authorized read.
$threadRequest = new GetConversationRequest($first->body->data->items[0]->id, limit: 25);
foreach ($conversations->getConversationPages($threadRequest) as $page) {
    // Each page retains its messages, attachment metadata and current read masks.
}
// getConversationMessages($threadRequest) also offers a lazy message iterator.
```

Request fields omitted by the caller remain omitted, so the server selects its native defaults. `filters` may be a typed `Filters` DTO or an exact JSON object string; raw JSON bytes are validated and preserved. The native filter keys are `mailbox_uuid`, `state`, `search`, paired `target_type`/`target_uuid`, and `sort`. Public response identities use `id`, `mailbox_id`, `owner_id`, `team_id`, `conversation_id` and `message_id`. Filters intentionally retain their owner's `*_uuid` keys. Detail requests accept only cursor and limit.

Both results contain `data` and `meta`. Metadata retains `authorization_version`, `readable_fields`, `filterable_fields` and `sortable_fields` from the current server. A hidden nullable field remains an explicit `null`; hidden collections remain `[]`. Required keys missing from the wire, leaked hidden values, private authority fields, mismatched thread identities or attachments outside their message page fail validation. Attachment objects contain metadata only, without a download URL or bytes. Response states remain strings for forward compatibility: `send_intent`, `unconfirmed` and `quarantine` never become confirmed sending or ready attachments on the client.

Iterators preserve the initial filter, cursor and limit, then reuse each opaque native next cursor. They reject repeated cursors, duplicate identities, changed authorization/masks, changed page size and an exhausted `maxPages` bound instead of returning an apparently complete traversal. Count values remain factual for each page. No replacement cursor or authority is reconstructed. Use explicit page methods when resuming from a persisted native cursor or handling current permission changes. Native 403, 404, 409, 422, 429 and 503 responses keep the canonical SDK error, original status, request id and headers.

The backend read descriptor requires `conversations:read`. At this Source freeze the existing backend read guard also requires `crm:read`; Root owns that admission reconciliation, route registration and activation. The SDK cannot grant either scope, prove record access or activate a backend surface. This delivery does not claim a mounted production endpoint, sandbox execution or a complete global CRM SDK inventory. Conversation writes, sending, attachment upload/download and write-original recovery currently have no V1 contract in this module and are excluded.

Offline verification covers typed codecs, native omission/null semantics, current masks, raw filters, pagination, delivery states and configured transport/error behavior. All code is in the regeneration-safe `src/Custom` namespace; no generated client or shared transport was changed.
