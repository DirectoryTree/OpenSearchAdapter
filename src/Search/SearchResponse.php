<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
 */
class SearchResponse implements RawResponseInterface
{
    /**
     * Create a new search response instance.
     *
     * @param  array<string, mixed>  $response
     */
    public function __construct(
        protected array $response,
    ) {}

    /**
     * Get the search hits.
     *
     * @return array<int, Hit>
     */
    public function hits(): array
    {
        $hits = $this->response['hits']['hits'];

        return array_map(static fn (array $hit) => new Hit($hit), $hits);
    }

    /**
     * Get the total number of matching documents.
     */
    public function total(): ?int
    {
        return $this->response['hits']['total']['value'] ?? null;
    }

    /**
     * Get the suggestions grouped by suggestion name.
     *
     * @return array<string, array<int, Suggestion>>
     */
    public function suggestions(): array
    {
        $suggest = $this->response['suggest'] ?? [];

        return array_map(
            static fn (array $suggestions) => array_map(
                static fn (array $suggestion) => new Suggestion($suggestion),
                $suggestions,
            ),
            $suggest,
        );
    }

    /**
     * Get the aggregations keyed by aggregation name.
     *
     * @return array<string, Aggregation>
     */
    public function aggregations(): array
    {
        $aggregations = $this->response['aggregations'] ?? [];

        return array_map(static fn (array $aggregation) => new Aggregation($aggregation), $aggregations);
    }

    /**
     * Get the raw OpenSearch search response.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->response;
    }
}
