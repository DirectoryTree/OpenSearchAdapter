<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use Illuminate\Support\Collection;

class SearchResponse implements RawResponseInterface
{
    /**
     * The raw OpenSearch search response.
     *
     * @var array<string, mixed>
     */
    protected array $response;

    /**
     * Create a new search response instance.
     *
     * @param  array<string, mixed>  $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Get the search hits.
     *
     * @return Collection<int, Hit>
     */
    public function hits(): Collection
    {
        $hits = $this->response['hits']['hits'];

        return collect($hits)->map(static function (array $hit) {
            return new Hit($hit);
        });
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
     * @return Collection<string, Collection<int, Suggestion>>
     */
    public function suggestions(): Collection
    {
        $suggest = $this->response['suggest'] ?? [];

        return collect($suggest)->map(static function (array $suggestions) {
            return collect($suggestions)->map(static function (array $suggestion) {
                return new Suggestion($suggestion);
            });
        });
    }

    /**
     * Get the aggregations keyed by aggregation name.
     *
     * @return Collection<string, Aggregation>
     */
    public function aggregations(): Collection
    {
        $aggregations = $this->response['aggregations'] ?? [];

        return collect($aggregations)->map(static function (array $aggregation) {
            return new Aggregation($aggregation);
        });
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
