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
class DocumentManager implements DocumentManagerInterface
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
    public function index(string $index, array $documents, bool $refresh = false, ?DocumentRouting $routing = null): static
    {
        $params = [
            'index' => $index,
            'refresh' => $refresh ? 'true' : 'false',
            'body' => [],
        ];

        foreach ($documents as $document) {
            array_push(
                $params['body'],
                ...$document->toBulkIndex($routing?->get($document->id()))
            );
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
     * @param  array<int, string>  $ids
     *
     * @throws BulkRequestException
     */
    public function delete(string $index, array $ids, bool $refresh = false, ?DocumentRouting $routing = null): static
    {
        $params = [
            'index' => $index,
            'refresh' => $refresh ? 'true' : 'false',
            'body' => [],
        ];

        foreach ($ids as $id) {
            $delete = ['_id' => $id];

            if ($routing && $routing->has($id)) {
                $delete['routing'] = $routing->get($id);
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
    public function deleteByQuery(string $index, array $query, bool $refresh = false): static
    {
        $this->client->deleteByQuery([
            'index' => $index,
            'refresh' => $refresh ? 'true' : 'false',
            'body' => compact('query'),
        ]);

        return $this;
    }

    /**
     * Search an index using the given search request.
     */
    public function search(string $index, SearchRequest $request): SearchResponse
    {
        $params = array_merge($request->toArray(), ['index' => $index]);

        $response = $this->client->search($params);

        return new SearchResponse($response);
    }
}
