<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Pagination\PageIterator;
use GuzzleHttp\Psr7\Response;

/** Only the actual native V1 list/get surface; the current server independently admits every request. */
final readonly class ConversationsClient
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

    /** @return CrmResponse<ConversationListResult> */
    public function listConversations(ListConversationsRequest $request): CrmResponse
    {
        $response = $this->transport->request('GET', 'crm/conversation', 'listCrmConversations', ConversationListResult::class,
            $request->query(), null, null, false, $this->headers);
        if ($response->statusCode() !== 200 || ($request->limit !== Omitted::Value && $response->body->data->limit !== $request->limit)) {
            throw new \UnexpectedValueException('Native Conversation list has an unexpected status or page limit.');
        }

        return $response;
    }

    /** Absence/invisibility stays the native HTTP404, never a fabricated empty thread.
     * @return CrmResponse<ConversationThreadResult>
     */
    public function getConversation(GetConversationRequest $request): CrmResponse
    {
        $response = $this->transport->request('GET', 'crm/conversation/'.rawurlencode($request->conversationId), 'getCrmConversation',
            ConversationThreadResult::class, $request->query(), null, null, false, $this->headers);
        if ($response->statusCode() !== 200 || strcasecmp($response->body->data->conversation->id, $request->conversationId) !== 0
            || ($request->limit !== Omitted::Value && $response->body->data->limit !== $request->limit)) {
            throw new \UnexpectedValueException('Native Conversation detail has an unexpected status, target or page limit.');
        }

        return $response;
    }

    /** @return \Generator<int,Conversation> */
    public function listConversationItems(ListConversationsRequest $request, int $maxPages = 1000): \Generator
    {
        if ($maxPages < 1) {
            throw new \InvalidArgumentException('maxPages must be positive.');
        }
        $pages = 0;
        $meta = null;
        $limit = null;
        $seenIds = [];
        $seenCursors = $request->cursor === Omitted::Value ? [] : [$request->cursor => true];
        /** @var list<Conversation> $items */
        $items = [];
        $iterator = new PageIterator(function (?string $cursor) use ($request, $maxPages, &$pages, &$meta, &$limit, &$seenIds, &$seenCursors, &$items): Response {
            if (++$pages > $maxPages) {
                throw new \OverflowException('Conversation pagination reached maxPages; resume with the last native cursor.');
            }
            $result = $this->listConversations($cursor === null ? $request : $request->withCursor($cursor))->body;
            if (($meta !== null && $result->meta != $meta) || ($limit !== null && $result->data->limit !== $limit)) {
                throw new \UnexpectedValueException('Native Conversation pagination changed current authorization, masks or page size.');
            }
            $meta ??= $result->meta;
            $limit ??= $result->data->limit;
            $items = $result->data->items;
            foreach ($items as $item) {
                Validation::unique($seenIds, $item->id);
            }
            $this->next($result->data->nextCursor, $seenCursors);

            return self::pageIndexes($items, $result->data->nextCursor);
        });
        foreach ($iterator->items() as $index) {
            if (! is_int($index) || ! isset($items[$index])) {
                throw new \UnexpectedValueException('Native Conversation pagination returned an invalid item.');
            }
            yield $items[$index];
        }
    }

    /** Each page retains its own attachments and current metadata; use this when consuming complete threads.
     * @return \Generator<int,ConversationThreadResult>
     */
    public function getConversationPages(GetConversationRequest $request, int $maxPages = 1000): \Generator
    {
        if ($maxPages < 1) {
            throw new \InvalidArgumentException('maxPages must be positive.');
        }
        $pages = 0;
        $meta = null;
        $limit = null;
        $seenMessages = [];
        $seenAttachments = [];
        $seenCursors = $request->cursor === Omitted::Value ? [] : [$request->cursor => true];
        /** @var ?ConversationThreadResult $current */
        $current = null;
        $iterator = new PageIterator(function (?string $cursor) use ($request, $maxPages, &$pages, &$meta, &$limit, &$seenMessages, &$seenAttachments, &$seenCursors, &$current): Response {
            if (++$pages > $maxPages) {
                throw new \OverflowException('Conversation thread pagination reached maxPages; resume with the last native cursor.');
            }
            $result = $this->getConversation($cursor === null ? $request : $request->withCursor($cursor))->body;
            if (($meta !== null && $result->meta != $meta) || ($limit !== null && $result->data->limit !== $limit)) {
                throw new \UnexpectedValueException('Native thread pagination changed current authorization, masks or page size.');
            }
            $meta ??= $result->meta;
            $limit ??= $result->data->limit;
            foreach ($result->data->messages as $message) {
                Validation::unique($seenMessages, $message->id);
            }
            foreach ($result->data->attachments as $attachment) {
                Validation::unique($seenAttachments, $attachment->id);
            }
            $this->next($result->data->nextCursor, $seenCursors);
            $current = $result;

            return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [0], 'has_more' => $result->data->nextCursor !== null, 'next_cursor' => $result->data->nextCursor,
            ], JSON_THROW_ON_ERROR));
        });
        foreach ($iterator->pages() as $_) {
            if (! $current instanceof ConversationThreadResult) {
                throw new \LogicException('A typed native thread page is required.');
            }
            yield $current;
        }
    }

    /** @return \Generator<int,Message> */
    public function getConversationMessages(GetConversationRequest $request, int $maxPages = 1000): \Generator
    {
        foreach ($this->getConversationPages($request, $maxPages) as $page) {
            foreach ($page->data->messages as $message) {
                yield $message;
            }
        }
    }

    /** @param array<string,true> $seen */
    private function next(?string $cursor, array &$seen): void
    {
        if ($cursor === null) {
            return;
        }
        Validation::cursor($cursor);
        if (isset($seen[$cursor])) {
            throw new \UnexpectedValueException('Native Conversation pagination repeated its opaque cursor.');
        }
        $seen[$cursor] = true;
    }

    /** @param list<Conversation> $items */
    private static function pageIndexes(array $items, ?string $cursor): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'data' => array_keys($items), 'has_more' => $cursor !== null, 'next_cursor' => $cursor,
        ], JSON_THROW_ON_ERROR));
    }
}
