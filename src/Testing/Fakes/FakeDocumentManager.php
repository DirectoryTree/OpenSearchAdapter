<?php

namespace DirectoryTree\OpenSearchAdapter\Testing\Fakes;

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManagerInterface;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentRouting;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use DirectoryTree\OpenSearchAdapter\Search\SearchResponse;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * Fakes OpenSearch document operations for tests.
 */
class FakeDocumentManager implements DocumentManagerInterface
{
    /**
     * The indexed document operations.
     *
     * @var array<int, array{index: string, documents: array<int, Document>, refresh: bool, routing: DocumentRouting|null}>
     */
    protected array $indexed = [];

    /**
     * The deleted document operations.
     *
     * @var array<int, array{index: string, ids: array<int, string>, refresh: bool, routing: DocumentRouting|null}>
     */
    protected array $deleted = [];

    /**
     * The delete by query operations.
     *
     * @var array<int, array{index: string, query: array<string, mixed>, refresh: bool}>
     */
    protected array $deletedByQuery = [];

    /**
     * The search operations.
     *
     * @var array<int, array{index: string, request: SearchRequest}>
     */
    protected array $searched = [];

    /**
     * Create a new fake document manager instance.
     */
    public function __construct(
        protected SearchResponse $response = new SearchResponse,
    ) {}

    /**
     * Index the given documents into OpenSearch.
     *
     * @param  array<int, Document>  $documents
     */
    public function index(string $index, array $documents, bool $refresh = false, ?DocumentRouting $routing = null): static
    {
        $this->indexed[] = compact('index', 'documents', 'refresh', 'routing');

        return $this;
    }

    /**
     * Delete the given documents from OpenSearch.
     *
     * @param  array<int, string>  $ids
     */
    public function delete(string $index, array $ids, bool $refresh = false, ?DocumentRouting $routing = null): static
    {
        $this->deleted[] = compact('index', 'ids', 'refresh', 'routing');

        return $this;
    }

    /**
     * Delete documents that match the given OpenSearch query.
     *
     * @param  array<string, mixed>  $query
     */
    public function deleteByQuery(string $index, array $query, bool $refresh = false): static
    {
        $this->deletedByQuery[] = compact('index', 'query', 'refresh');

        return $this;
    }

    /**
     * Search an index using the given search request.
     */
    public function search(string $index, SearchRequest $request): SearchResponse
    {
        $this->searched[] = compact('index', 'request');

        return $this->response;
    }

    /**
     * Assert that the given documents were indexed.
     *
     * @param  array<int, Document>  $documents
     */
    public function assertIndexed(string $index, array $documents, bool $refresh = false, ?DocumentRouting $routing = null): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'documents', 'refresh', 'routing'), $this->indexed);

        return $this;
    }

    /**
     * Assert that the given document IDs were deleted.
     *
     * @param  array<int, string>  $ids
     */
    public function assertDeleted(string $index, array $ids, bool $refresh = false, ?DocumentRouting $routing = null): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'ids', 'refresh', 'routing'), $this->deleted);

        return $this;
    }

    /**
     * Assert that the given query was deleted.
     *
     * @param  array<string, mixed>  $query
     */
    public function assertDeletedByQuery(string $index, array $query, bool $refresh = false): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'query', 'refresh'), $this->deletedByQuery);

        return $this;
    }

    /**
     * Assert that the given index was searched.
     */
    public function assertSearched(string $index, SearchRequest $request): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'request'), $this->searched);

        return $this;
    }
}
