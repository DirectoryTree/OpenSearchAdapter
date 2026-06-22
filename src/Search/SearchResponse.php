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
     * Get the search execution time in milliseconds.
     */
    public function took(): ?int
    {
        return $this->response['took'] ?? null;
    }

    /**
     * Determine if the search timed out.
     */
    public function timedOut(): bool
    {
        return $this->response['timed_out'] ?? false;
    }

    /**
     * Get the search shard statistics.
     */
    public function shards(): ?ShardStatistics
    {
        return isset($this->response['_shards'])
            ? new ShardStatistics($this->response['_shards'])
            : null;
    }

    /**
     * Get the total hit count metadata.
     */
    public function totalHits(): ?TotalHits
    {
        return isset($this->response['hits']['total'])
            ? new TotalHits($this->response['hits']['total'])
            : null;
    }

    /**
     * Get the total number of matching documents.
     */
    public function total(): ?int
    {
        return $this->totalHits()?->value();
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
