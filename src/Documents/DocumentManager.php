<?php

namespace DirectoryTree\OpenSearchAdapter\Documents;

use DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use DirectoryTree\OpenSearchAdapter\Search\SearchResponse;
use OpenSearch\Client;

/**
 * @see https://docs.opensearch.org/latest/api-reference/document-apis/bulk/
 * @see https://docs.opensearch.org/latest/api-reference/document-apis/delete-by-query/
 * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
 */
class DocumentManager
{
    /**
     * Create a new document manager instance.
     */
    public function __construct(
        protected Client $client,
    ) {}

    /**
     * Index the given documents into OpenSearch.
     *
     * @param  array<int, Document>  $documents
     *
     * @throws BulkRequestException
     */
    public function index(
        string $indexName,
        array $documents,
        bool $refresh = false,
        ?Routing $routing = null
    ): self {
        $params = [
            'index' => $indexName,
            'refresh' => $refresh ? 'true' : 'false',
            'body' => [],
        ];

        foreach ($documents as $document) {
            $index = ['_id' => $document->id()];

            if ($routing && $routing->has($document->id())) {
                $index['routing'] = $routing->get($document->id());
            }

            $params['body'][] = compact('index');
            $params['body'][] = $document->content();
        }

        $response = $this->client->bulk($params);

        if ($response['errors']) {
            throw BulkRequestException::fromResponse($response);
        }

        return $this;
    }

    /**
     * Delete the given documents from OpenSearch.
     *
     * @param  array<int, string>  $documentIds
     *
     * @throws BulkRequestException
     */
    public function delete(
        string $indexName,
        array $documentIds,
        bool $refresh = false,
        ?Routing $routing = null
    ): self {
        $params = [
            'index' => $indexName,
            'refresh' => $refresh ? 'true' : 'false',
            'body' => [],
        ];

        foreach ($documentIds as $documentId) {
            $delete = ['_id' => $documentId];

            if ($routing && $routing->has($documentId)) {
                $delete['routing'] = $routing->get($documentId);
            }

            $params['body'][] = compact('delete');
        }

        $response = $this->client->bulk($params);

        if ($response['errors']) {
            throw BulkRequestException::fromResponse($response);
        }

        return $this;
    }

    /**
     * Delete documents that match the given OpenSearch query.
     *
     * @param  array<string, mixed>  $query
     */
    public function deleteByQuery(string $indexName, array $query, bool $refresh = false): self
    {
        $params = [
            'index' => $indexName,
            'refresh' => $refresh ? 'true' : 'false',
            'body' => compact('query'),
        ];

        $this->client->deleteByQuery($params);

        return $this;
    }

    /**
     * Search an index using the given search request.
     */
    public function search(string $indexName, SearchRequest $request): SearchResponse
    {
        $params = array_merge($request->toArray(), ['index' => $indexName]);
        $response = $this->client->search($params);

        return new SearchResponse($response);
    }
}
