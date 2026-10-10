<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\Model;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\UnconfirmedMutationException;
use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\BeforeRequestHook;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class KnowledgeBaseHttpTest extends TestCase
{
    private const ID = '019a4afd-5000-7000-8000-000000000001';

    private const EFFECT = '019a4afd-5000-7000-8000-000000000002';

    private const TAXONOMY = '019a4afd-5000-7000-8000-000000000003';

    private const KEY = 'persisted original knowledge intent';

    /** @var list<array<string, mixed>> */
    private array $history = [];

    /** @var \ArrayObject<int, string> */
    private \ArrayObject $operations;

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses): CrmClient
    {
        $this->operations = new \ArrayObject();
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_knowledge_fixture'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone())
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class($this->operations) implements BeforeRequestHook
        {
            /** @param \ArrayObject<int, string> $operations */
            public function __construct(private \ArrayObject $operations)
            {
            }

            public function beforeRequest(BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->operations[] = $context->operationID;

                return $request;
            }
        });

        return new CrmClient($sdk);
    }

    /** @param array<string, mixed> $data @param array<string, string> $headers */
    private function response(array $data, int $status = 200, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'] + $headers,
            json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }

    /** @return array<string, mixed> */
    private static function articleIntent(int $version = 0): array
    {
        $body = ['expected_version' => $version, 'title' => 'Native article', 'body' => 'Editorial content',
            'editorial_locale' => 'ca', 'category_ids' => []];
        if ($version === 0) {
            $body['slug'] = 'native-article';
        }

        return $body;
    }

    /** @return array<string, mixed> */
    private static function articleReceipt(string $operation, int $version = 7): array
    {
        return ['effect_id' => self::EFFECT, 'operation' => 'knowledge_articles.'.$operation,
            'expected_version' => $version, 'article' => ['id' => self::ID, 'version' => $version + 1], 'confirmed' => true];
    }

    /** @return array<string, mixed> */
    private static function categoryIntent(): array
    {
        return ['taxonomy_id' => self::TAXONOMY, 'expected_taxonomy_version' => 4, 'expected_version' => null,
            'name' => 'Native category', 'slug' => 'native-category', 'parent_id' => null, 'visibility' => 'internal', 'status' => 'active'];
    }

    /** @return array<string, mixed> */
    private static function categoryReceipt(): array
    {
        return ['effect_id' => self::EFFECT, 'operation' => 'knowledge_articles.category_save',
            'expected_version' => null, 'expected_taxonomy_version' => 4, 'category' => ['id' => self::ID,
                'version' => 1, 'taxonomy_id' => self::TAXONOMY, 'taxonomy_version' => 5, 'parent_id' => null], 'confirmed' => true];
    }

    /** @return array<string, mixed> */
    private static function centerIntent(): array
    {
        return ['confirmed' => true, 'expected_version' => 0, 'slug' => 'native-center',
            'display_name' => 'Native center', 'locale' => 'en', 'article_slugs' => ['native-article']];
    }

    /** @return array<string, mixed> */
    private static function centerReceipt(bool $enabled): array
    {
        $version = $enabled ? 0 : 3;

        return ['confirmed' => true, 'operation' => 'public_help_centers.'.($enabled ? 'publish' : 'unpublish'),
            'effect_id' => self::EFFECT, 'expected_version' => $version, 'center' => ['id' => self::ID,
                'version' => $version + 1, 'slug' => 'native-center', 'display_name' => 'Native center',
                'locale' => 'en', 'article_slugs' => ['native-article'], 'enabled' => $enabled]];
    }

    /** @return iterable<string, array<string|array<string, mixed>|int>> */
    public static function operations(): iterable
    {
        yield 'article versions' => ['knowledgeBase', 'crmGetKnowledgeArticleVersions', Model\CrmGetKnowledgeArticleVersionsParameters::class,
            ['article' => self::ID], 'GET', '/v1/crm/knowledge/articles/'.self::ID.'/versions', ['items' => []], 200];
        yield 'category list' => ['knowledgeBase', 'listKnowledgeCategories', Model\ListKnowledgeCategoriesParameters::class,
            [], 'GET', '/v1/crm/knowledge/categories', ['items' => [], 'total' => 0], 200];
        yield 'category recovery' => ['knowledgeBase', 'recoverKnowledgeCategoryReceipt', Model\RecoverKnowledgeCategoryReceiptParameters::class,
            ['idempotency_key' => self::KEY], 'GET', '/v1/crm/knowledge/categories/receipts/category-save', self::categoryReceipt(), 200];
        yield 'category save' => ['knowledgeBase', 'saveKnowledgeCategory', Model\SaveKnowledgeCategoryParameters::class,
            ['body' => self::categoryIntent(), 'idempotency_key' => self::KEY], 'POST', '/v1/crm/knowledge/categories/save', self::categoryReceipt(), 201];
        yield 'article search' => ['knowledgeBase', 'crmSearchKnowledgeArticles', Model\CrmSearchKnowledgeArticlesParameters::class,
            ['query' => 'billing', 'locale' => 'ca', 'page' => 2, 'per_page' => 1], 'GET', '/v1/crm/knowledge/articles', ['items' => [], 'total' => 0, 'page' => 2, 'per_page' => 1], 200];
        yield 'article create' => ['knowledgeBase', 'crmCreateKnowledgeArticle', Model\CrmCreateKnowledgeArticleParameters::class,
            ['body' => self::articleIntent(), 'idempotency_key' => self::KEY], 'POST', '/v1/crm/knowledge/articles', self::articleReceipt('create', 0), 201];
        yield 'article show' => ['knowledgeBase', 'crmGetKnowledgeArticle', Model\CrmGetKnowledgeArticleParameters::class,
            ['article' => self::ID], 'GET', '/v1/crm/knowledge/articles/'.self::ID, ['id' => self::ID, 'version' => 20], 200];
        yield 'article save' => ['knowledgeBase', 'crmSaveKnowledgeArticle', Model\CrmSaveKnowledgeArticleParameters::class,
            ['article' => self::ID, 'body' => self::articleIntent(7), 'idempotency_key' => self::KEY], 'PUT', '/v1/crm/knowledge/articles/'.self::ID, self::articleReceipt('save'), 200];
        yield 'ticket suggestions' => ['knowledgeBase', 'suggestServiceArticles', Model\SuggestServiceArticlesParameters::class,
            ['ticket' => self::ID, 'ticket_version' => 9, 'audience' => 'internal', 'limit' => 1], 'GET', '/v1/crm/knowledge/tickets/'.self::ID.'/suggestions',
            ['items' => [], 'total' => 0, 'ticket_id' => self::ID, 'ticket_version' => 9, 'audience' => 'internal'], 200];
        foreach (['submit' => 'Submit', 'approve' => 'Approve', 'publish' => 'Publish', 'unpublish' => 'Unpublish', 'archive' => 'Archive', 'link' => 'Link'] as $operation => $suffix) {
            $body = ['expected_version' => 7];
            if ($operation === 'publish') {
                $body += ['published_version' => 2, 'audience' => 'public'];
            } elseif ($operation === 'link') {
                $body += ['target_type' => 'ticket', 'target_id' => self::TAXONOMY, 'target_version' => 11];
            }
            yield 'article '.$operation => ['knowledgeBase', 'crm'.$suffix.'KnowledgeArticle', 'Factuarea\\Sdk\\Custom\\Crm\\Model\\Crm'.$suffix.'KnowledgeArticleParameters',
                ['article' => self::ID, 'body' => $body, 'idempotency_key' => self::KEY], 'POST', '/v1/crm/knowledge/articles/'.self::ID.'/'.$operation, self::articleReceipt($operation), 200];
        }
        foreach (['create' => 'Create', 'save' => 'Save', 'submit' => 'Submit', 'approve' => 'Approve', 'publish' => 'Publish', 'unpublish' => 'Unpublish', 'archive' => 'Archive', 'link' => 'Link'] as $operation => $suffix) {
            yield 'original '.$operation.' recovery' => ['knowledgeBase', 'crmRecoverKnowledgeArticle'.$suffix.'Receipt', 'Factuarea\\Sdk\\Custom\\Crm\\Model\\CrmRecoverKnowledgeArticle'.$suffix.'ReceiptParameters',
                ['idempotency_key' => self::KEY], 'GET', '/v1/crm/knowledge/receipts/knowledge_articles.'.$operation, self::articleReceipt($operation, $operation === 'create' ? 0 : 7), 200];
        }
        yield 'center administration' => ['publicHelpCenter', 'publicApiV1CrmPublicHelpCenterGet', Model\PublicApiV1CrmPublicHelpCenterGetParameters::class,
            [], 'GET', '/v1/crm/public-help-center', ['center' => null], 200];
        yield 'center publish' => ['publicHelpCenter', 'publicApiV1CrmPublicHelpCenterPublish', Model\PublicApiV1CrmPublicHelpCenterPublishParameters::class,
            ['body' => self::centerIntent(), 'idempotency_key' => self::KEY], 'POST', '/v1/crm/public-help-center/publish', self::centerReceipt(true), 201];
        yield 'center unpublish' => ['publicHelpCenter', 'publicApiV1CrmPublicHelpCenterUnpublish', Model\PublicApiV1CrmPublicHelpCenterUnpublishParameters::class,
            ['center' => self::ID, 'body' => ['confirmed' => true, 'expected_version' => 3], 'idempotency_key' => self::KEY], 'POST', '/v1/crm/public-help-center/'.self::ID.'/unpublish', self::centerReceipt(false), 200];
        yield 'center publish recovery' => ['publicHelpCenter', 'publicApiV1CrmPublicHelpCenterReceiptPublish', Model\PublicApiV1CrmPublicHelpCenterReceiptPublishParameters::class,
            ['idempotency_key' => self::KEY], 'GET', '/v1/crm/public-help-center/receipts/publish', self::centerReceipt(true), 200];
        yield 'center unpublish recovery' => ['publicHelpCenter', 'publicApiV1CrmPublicHelpCenterReceiptUnpublish', Model\PublicApiV1CrmPublicHelpCenterReceiptUnpublishParameters::class,
            ['idempotency_key' => self::KEY], 'GET', '/v1/crm/public-help-center/receipts/unpublish', self::centerReceipt(false), 200];
    }

    /** @param class-string<WireModel> $requestType @param array<string, mixed> $input @param array<string, mixed> $data */
    #[DataProvider('operations')]
    public function test_all_28_operations_send_native_routes_and_typed_closed_intents(string $group, string $method, string $requestType,
        array $input, string $verb, string $path, array $data, int $status): void
    {
        $client = $this->client([$this->response($data, $status)]);
        $parameters = $requestType::fromArray($input + ['X-Active-Profile' => self::TAXONOMY, 'Factuarea-Version' => '2026-10-01']);
        $result = $client->{$group}->{$method}($parameters);
        self::assertInstanceOf(WireModel::class, $result->body);
        self::assertSame($data, json_decode($result->body->toJson(), true)['data']);
        self::assertSame($status, $result->statusCode());
        self::assertSame($input['idempotency_key'] ?? null, $result->idempotencyKey);
        $request = $this->history[0]['request'];
        self::assertSame($verb, $request->getMethod());
        self::assertSame($path, $request->getUri()->getPath());
        self::assertSame('Bearer fact_test_knowledge_fixture', $request->getHeaderLine('Authorization'));
        self::assertSame(self::TAXONOMY, $request->getHeaderLine('X-Active-Profile'));
        self::assertSame('2026-10-01', $request->getHeaderLine('Factuarea-Version'));
        self::assertSame($input['idempotency_key'] ?? '', $request->getHeaderLine('Idempotency-Key'));
        if (isset($input['body'])) {
            self::assertSame($input['body'], json_decode((string) $request->getBody(), true));
        } else {
            self::assertSame('', (string) $request->getBody());
        }
        if ($group === 'publicHelpCenter') {
            $operation = match ($method) {
                'publicApiV1CrmPublicHelpCenterGet' => 'get',
                'publicApiV1CrmPublicHelpCenterPublish' => 'publish',
                'publicApiV1CrmPublicHelpCenterUnpublish' => 'unpublish',
                'publicApiV1CrmPublicHelpCenterReceiptPublish' => 'receipt-publish',
                'publicApiV1CrmPublicHelpCenterReceiptUnpublish' => 'receipt-unpublish',
            };
            self::assertSame('public-api.v1.crm-public-help-center.'.$operation, $this->operations[0]);
        } else {
            self::assertSame($method, $this->operations[0]);
        }
        if (str_contains($path, '/receipts/')) {
            self::assertSame('', $request->getUri()->getQuery());
        }
    }

    public function test_recovery_keeps_original_snapshot_after_a_newer_head_and_does_not_dispatch(): void
    {
        $client = $this->client([
            $this->response(self::articleReceipt('save')),
            $this->response(['id' => self::ID, 'version' => 20]),
            $this->response(self::articleReceipt('save'), 200, ['Idempotent-Replayed' => 'true']),
        ]);
        $saved = $client->knowledgeBase->crmSaveKnowledgeArticle(new Model\CrmSaveKnowledgeArticleParameters(
            article: self::ID, body: Model\KnowledgeArticleSaveRequest::fromArray(self::articleIntent(7)), idempotencyKey: self::KEY));
        $head = $client->knowledgeBase->crmGetKnowledgeArticle(new Model\CrmGetKnowledgeArticleParameters(article: self::ID));
        $recovered = $client->knowledgeBase->crmRecoverKnowledgeArticleSaveReceipt(new Model\CrmRecoverKnowledgeArticleSaveReceiptParameters(idempotencyKey: self::KEY));
        self::assertInstanceOf(Model\KnowledgeArticleSaveOriginalReceipt::class, $recovered->body->data);
        self::assertSame(20, $head->body->data->version);
        self::assertSame(7, $recovered->body->data->expectedVersion);
        self::assertSame(8, $recovered->body->data->article->version);
        self::assertSame($saved->body->data->effectId, $recovered->body->data->effectId);
        self::assertSame($saved->idempotencyKey, $recovered->idempotencyKey);
        self::assertTrue($recovered->replayed());
        self::assertSame(Omitted::Value, $recovered->body->data->article->title);
        self::assertCount(3, $this->history);
        self::assertSame('GET', $this->history[2]['request']->getMethod());
    }

    public function test_masked_projection_nullable_publication_and_required_category_nulls_are_preserved(): void
    {
        $article = Model\KnowledgeArticle::fromArray(['id' => self::ID, 'version' => 8, 'published_version' => null, 'audience' => null]);
        self::assertSame(Omitted::Value, $article->title);
        self::assertSame(Omitted::Value, $article->body);
        self::assertNull($article->publishedVersion);
        self::assertNull($article->audience);
        self::assertArrayNotHasKey('title', $article->toArray());
        self::assertArrayHasKey('audience', $article->toArray());
        $category = Model\KnowledgeCategorySaveRequest::fromArray(self::categoryIntent());
        self::assertSame(Omitted::Value, $category->id);
        self::assertSame(self::categoryIntent(), json_decode($category->toJson(), true));
        $receipt = Model\KnowledgeCategoryOriginalReceipt::fromArray(self::categoryReceipt());
        self::assertInstanceOf(Model\KnowledgeCategoryOriginalReceiptCategory::class, $receipt->category);
        self::assertNull($receipt->expectedVersion);
        self::assertNull($receipt->category->parentId);
        self::assertSame(Omitted::Value, $receipt->category->name);
        self::assertSame(4, $receipt->expectedTaxonomyVersion);
        self::assertSame(5, $receipt->category->taxonomyVersion);
    }

    public function test_center_snapshots_and_absent_administration_are_real_typed_nullable_models(): void
    {
        $client = $this->client([$this->response(['center' => null]), $this->response(self::centerReceipt(true)), $this->response(self::centerReceipt(false))]);
        $absent = $client->publicHelpCenter->publicApiV1CrmPublicHelpCenterGet(new Model\PublicApiV1CrmPublicHelpCenterGetParameters());
        $published = $client->publicHelpCenter->publicApiV1CrmPublicHelpCenterReceiptPublish(new Model\PublicApiV1CrmPublicHelpCenterReceiptPublishParameters(idempotencyKey: self::KEY));
        $withdrawn = $client->publicHelpCenter->publicApiV1CrmPublicHelpCenterReceiptUnpublish(new Model\PublicApiV1CrmPublicHelpCenterReceiptUnpublishParameters(idempotencyKey: self::KEY));
        self::assertNull($absent->body->data->center);
        self::assertInstanceOf(Model\PublicHelpCenterAdministrativeCenter::class, $published->body->data->center);
        self::assertInstanceOf(Model\PublicHelpCenterAdministrativeCenter::class, $withdrawn->body->data->center);
        self::assertTrue($published->body->data->center->enabled);
        self::assertFalse($withdrawn->body->data->center->enabled);
        self::assertSame(4, $withdrawn->body->data->center->version);
    }

    public function test_revision_category_and_suggestion_lists_hydrate_real_masked_dtos(): void
    {
        $client = $this->client([
            $this->response(['items' => [['id' => self::ID, 'version' => 8, 'revision_number' => 2, 'category_ids' => []]]]),
            $this->response(['items' => [['id' => self::ID, 'taxonomy_id' => self::TAXONOMY, 'version' => 1, 'taxonomy_version' => 5,
                'name' => 'Native category', 'slug' => 'native-category', 'parent_id' => null, 'visibility' => 'internal']], 'total' => 1]),
            $this->response(['items' => [['id' => self::ID, 'version' => 8, 'published_version' => 2, 'revision_number' => 2, 'audience' => 'internal']],
                'total' => 1, 'ticket_id' => self::TAXONOMY, 'ticket_version' => 9, 'audience' => 'internal']),
        ]);
        $revision = $client->knowledgeBase->crmGetKnowledgeArticleVersions(new Model\CrmGetKnowledgeArticleVersionsParameters(article: self::ID))->body->data->items[0];
        $category = $client->knowledgeBase->listKnowledgeCategories(new Model\ListKnowledgeCategoriesParameters())->body->data->items[0];
        $suggestion = $client->knowledgeBase->suggestServiceArticles(new Model\SuggestServiceArticlesParameters(ticket: self::TAXONOMY, ticketVersion: 9,
            query: 'VAT & tax', locale: 'ca', audience: 'internal', limit: 1))->body->data->items[0];
        self::assertInstanceOf(Model\KnowledgeArticleRevision::class, $revision);
        self::assertSame([], $revision->categoryIds);
        self::assertSame(Omitted::Value, $revision->body);
        self::assertInstanceOf(Model\KnowledgeCategory::class, $category);
        self::assertNull($category->parentId);
        self::assertInstanceOf(Model\KnowledgeArticleSuggestion::class, $suggestion);
        self::assertSame(2, $suggestion->publishedVersion);
        self::assertSame(Omitted::Value, $suggestion->title);
        parse_str($this->history[2]['request']->getUri()->getQuery(), $query);
        self::assertSame(['ticket_version' => '9', 'query' => 'VAT & tax', 'locale' => 'ca', 'audience' => 'internal', 'limit' => '1'], $query);
    }

    public function test_search_iterator_retains_query_locale_page_size_masks_and_caller_request(): void
    {
        $client = $this->client([
            $this->response(['items' => [['id' => self::ID, 'version' => 3]], 'total' => 3, 'page' => 2, 'per_page' => 1]),
            $this->response(['items' => [['id' => self::EFFECT, 'version' => 4, 'published_version' => null]], 'total' => 3, 'page' => 3, 'per_page' => 1]),
        ]);
        $parameters = new Model\CrmSearchKnowledgeArticlesParameters(query: 'tax & VAT', locale: 'en', page: 2, perPage: 1);
        $items = iterator_to_array($client->knowledgeBase->crmSearchKnowledgeArticlesItems($parameters));
        self::assertCount(2, $items);
        self::assertInstanceOf(Model\KnowledgeArticle::class, $items[0]);
        self::assertSame(Omitted::Value, $items[0]->title);
        self::assertNull($items[1]->publishedVersion);
        self::assertSame(2, $parameters->page);
        foreach ($this->history as $index => $entry) {
            parse_str($entry['request']->getUri()->getQuery(), $query);
            self::assertSame(['query' => 'tax & VAT', 'locale' => 'en', 'page' => (string) ($index + 2), 'per_page' => '1'], $query);
        }
    }

    public function test_search_max_pages_fails_explicitly_before_another_request(): void
    {
        $client = $this->client([$this->response(['items' => [['id' => self::ID, 'version' => 3]], 'total' => 2, 'page' => 1, 'per_page' => 1])]);
        try {
            iterator_to_array($client->knowledgeBase->crmSearchKnowledgeArticlesItems(new Model\CrmSearchKnowledgeArticlesParameters(perPage: 1), maxPages: 1));
            self::fail('Expected the explicit pagination bound.');
        } catch (\OverflowException) {
            self::assertCount(1, $this->history);
        }
    }

    public function test_search_repeated_native_page_is_rejected(): void
    {
        $page = ['items' => [['id' => self::ID, 'version' => 3]], 'total' => 3, 'page' => 1, 'per_page' => 1];
        $client = $this->client([$this->response($page), $this->response($page)]);
        try {
            iterator_to_array($client->knowledgeBase->crmSearchKnowledgeArticlesItems(new Model\CrmSearchKnowledgeArticlesParameters(perPage: 1)));
            self::fail('Expected repeated native page failure.');
        } catch (\UnexpectedValueException $error) {
            self::assertStringContainsString('repeated', $error->getMessage());
            self::assertCount(2, $this->history);
        }
    }

    /** @return iterable<string, array{class-string<WireModel>, array<string, mixed>}> */
    public static function invalidIntents(): iterable
    {
        yield 'category create omits ID' => [Model\KnowledgeCategorySaveRequest::class, self::categoryIntent() + ['id' => self::ID]];
        yield 'category edit requires ID' => [Model\KnowledgeCategorySaveRequest::class, array_replace(self::categoryIntent(), ['expected_version' => 3])];
        yield 'category edit positive CAS' => [Model\KnowledgeCategorySaveRequest::class, array_replace(self::categoryIntent(), ['id' => self::ID, 'expected_version' => 0])];
        yield 'taxonomy original positive CAS' => [Model\KnowledgeCategorySaveRequest::class, array_replace(self::categoryIntent(), ['expected_taxonomy_version' => 0])];
        yield 'category cannot create public ID' => [Model\KnowledgeCategorySaveRequest::class, self::categoryIntent() + ['effect_id' => self::EFFECT]];
        yield 'article creation zero CAS' => [Model\KnowledgeArticleCreateRequest::class, array_replace(self::articleIntent(), ['expected_version' => 1])];
        yield 'article must not supply UUID' => [Model\KnowledgeArticleCreateRequest::class, self::articleIntent() + ['id' => self::ID]];
        yield 'article must not supply Company' => [Model\KnowledgeArticleCreateRequest::class, self::articleIntent() + ['company_id' => self::ID]];
        yield 'article must not supply actor' => [Model\KnowledgeArticleCreateRequest::class, self::articleIntent() + ['actor_id' => self::ID]];
        yield 'article content cannot be null' => [Model\KnowledgeArticleSaveRequest::class, array_replace(self::articleIntent(3), ['body' => null])];
        yield 'article slug immutable on save' => [Model\KnowledgeArticleSaveRequest::class, self::articleIntent(3) + ['slug' => 'new-slug']];
        yield 'article edit CAS before overflow' => [Model\KnowledgeArticleSaveRequest::class, array_replace(self::articleIntent(3), ['expected_version' => PHP_INT_MAX])];
        yield 'article categories unique' => [Model\KnowledgeArticleCreateRequest::class, array_replace(self::articleIntent(), ['category_ids' => [self::ID, self::ID]])];
        yield 'article locale catalog' => [Model\KnowledgeArticleCreateRequest::class, array_replace(self::articleIntent(), ['editorial_locale' => 'fr'])];
        yield 'link target UUIDv7' => [Model\KnowledgeArticleLinkRequest::class, ['expected_version' => 1, 'target_type' => 'ticket', 'target_id' => '7', 'target_version' => 2]];
        yield 'publish requires explicit confirmation' => [Model\PublicHelpCenterAdministrativePublishRequest::class, array_replace(self::centerIntent(), ['confirmed' => false])];
        yield 'publish existing center needs positive CAS' => [Model\PublicHelpCenterAdministrativePublishRequest::class, self::centerIntent() + ['id' => self::ID]];
        yield 'publish absent center zero CAS' => [Model\PublicHelpCenterAdministrativePublishRequest::class, array_replace(self::centerIntent(), ['expected_version' => 1])];
        yield 'withdrawal positive CAS' => [Model\PublicHelpCenterAdministrativeUnpublishRequest::class, ['confirmed' => true, 'expected_version' => 0]];
        yield 'withdrawal cannot replace manifest' => [Model\PublicHelpCenterAdministrativeUnpublishRequest::class, ['confirmed' => true, 'expected_version' => 2, 'article_slugs' => []]];
        yield 'recovery key required' => [Model\CrmRecoverKnowledgeArticleSaveReceiptParameters::class, []];
        yield 'recovery original printable ASCII' => [Model\PublicApiV1CrmPublicHelpCenterReceiptPublishParameters::class, ['idempotency_key' => "new\nkey"]];
        yield 'recovery cannot add latest-head identity' => [Model\CrmRecoverKnowledgeArticleSaveReceiptParameters::class, ['idempotency_key' => self::KEY, 'article' => self::ID]];
        $wrong = self::centerReceipt(true);
        $wrong['center']['enabled'] = false;
        yield 'publish receipt allOf literal enabled' => [Model\PublicHelpCenterOriginalPublishReceipt::class, $wrong];
        $wrong = self::centerReceipt(false);
        unset($wrong['center']['article_slugs']);
        yield 'center mask cannot fabricate partial DTO' => [Model\PublicHelpCenterOriginalUnpublishReceipt::class, $wrong];
    }

    /** @param class-string<WireModel> $type @param array<string, mixed> $values */
    #[DataProvider('invalidIntents')]
    public function test_native_closed_and_conditional_contracts_reject_invalid_input(string $type, array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $type::fromArray($values);
    }

    /** @return iterable<string, array{int, string, string}> */
    public static function errors(): iterable
    {
        yield 'scope' => [403, 'authorization_error', 'insufficient_scope'];
        yield 'opaque receipt' => [404, 'not_found_error', 'resource_not_found'];
        yield 'original CAS' => [409, 'conflict_error', 'crm_version_conflict'];
        yield 'validation' => [422, 'invalid_request_error', 'parameter_invalid'];
        yield 'rate limit' => [429, 'rate_limit_error', 'rate_limit_exceeded'];
        yield 'ambiguous server failure' => [500, 'api_error', 'internal_error'];
    }

    #[DataProvider('errors')]
    public function test_native_errors_keep_original_key_validation_fields_and_retry_after(int $status, string $type, string $code): void
    {
        $body = ['error' => ['type' => $type, 'code' => $code, 'message' => 'Native knowledge error', 'param' => 'expected_version',
            'request_id' => 'req_knowledge_fixture', 'errors' => [['param' => 'expected_version', 'code' => 'parameter_invalid', 'message' => 'Native CAS']]]];
        $client = $this->client([new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '5'], json_encode($body, JSON_THROW_ON_ERROR))]);
        try {
            $client->knowledgeBase->crmArchiveKnowledgeArticle(new Model\CrmArchiveKnowledgeArticleParameters(article: self::ID,
                body: new Model\KnowledgeArticleArchiveRequest(expectedVersion: 7), idempotencyKey: self::KEY));
            self::fail('Expected the native HTTP error.');
        } catch (ErrorThrowable $error) {
            self::assertSame($code, $error->container->error->code);
            self::assertSame('expected_version', $error->container->error->param);
            self::assertSame('expected_version', $error->container->error->errors[0]->param);
            self::assertSame('req_knowledge_fixture', $error->container->error->requestId);
            self::assertSame($status, $error->container->rawResponse->getStatusCode());
            self::assertSame('5', $error->container->rawResponse->getHeaderLine('Retry-After'));
            self::assertSame(self::KEY, $this->history[0]['request']->getHeaderLine('Idempotency-Key'));
            self::assertCount(1, $this->history);
        }
    }

    public function test_ambiguous_center_effect_is_unconfirmed_and_explicit_recovery_is_only_a_get(): void
    {
        $failure = new ConnectException('write timeout', new Request('POST', 'https://fixture.invalid'));
        $client = $this->client([$failure, $this->response(self::centerReceipt(true))]);
        try {
            $client->publicHelpCenter->publicApiV1CrmPublicHelpCenterPublish(new Model\PublicApiV1CrmPublicHelpCenterPublishParameters(
                body: Model\PublicHelpCenterAdministrativePublishRequest::fromArray(self::centerIntent()), idempotencyKey: self::KEY));
            self::fail('Expected an unconfirmed original effect.');
        } catch (UnconfirmedMutationException $error) {
            self::assertSame(self::KEY, $error->idempotencyKey);
            self::assertSame('public-api.v1.crm-public-help-center.publish', $error->operationId);
            self::assertSame($failure, $error->getPrevious());
            self::assertCount(1, $this->history);
        }
        $result = $client->publicHelpCenter->publicApiV1CrmPublicHelpCenterReceiptPublish(new Model\PublicApiV1CrmPublicHelpCenterReceiptPublishParameters(idempotencyKey: self::KEY));
        self::assertSame(self::KEY, $result->idempotencyKey);
        self::assertSame('GET', $this->history[1]['request']->getMethod());
        self::assertCount(2, $this->history);
    }
}
