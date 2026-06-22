<?php

namespace DirectoryTree\OpenSearchAdapter\Documents;

use DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use DirectoryTree\OpenSearchAdapter\Search\SearchResponse;

/**
 * Manages OpenSearch documents.
 */
interface DocumentManagerInterface
{
    /**
     * Index the given documents into OpenSearch.
     *
     * @param  array<int, Document>  $documents
     *
     * @throws BulkRequestException
     */
    public function index(string $index, array $documents, bool $refresh = false, ?DocumentRouting $routing = null): static;

    /**
     * Delete the given documents from OpenSearch.
     *
     * @param  array<int, string>  $ids
     *
     * @throws BulkRequestException
     */
    public function delete(string $index, array $ids, bool $refresh = false, ?DocumentRouting $routing = null): static;

    /**
     * Delete documents that match the given OpenSearch query.
     *
     * @param  array<string, mixed>  $query
     */
    public function deleteByQuery(string $index, array $query, bool $refresh = false): static;

    /**
     * Search an index using the given search request.
     */
    public function search(string $index, SearchRequest $request): SearchResponse;
}
