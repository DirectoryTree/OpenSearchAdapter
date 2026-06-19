<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

class SearchRequest
{
    /**
     * The OpenSearch search request payload.
     *
     * @var array<string, mixed>
     */
    protected array $request = [];

    /**
     * Create a new search request instance.
     *
     * @param  array<string, mixed>|null  $query
     */
    public function __construct(?array $query = null)
    {
        if (isset($query)) {
            $this->request['body']['query'] = $query;
        }
    }

    /**
     * Set the highlight definition.
     *
     * @param  array<string, mixed>  $highlight
     */
    public function highlight(array $highlight): self
    {
        $this->request['body']['highlight'] = $highlight;

        return $this;
    }

    /**
     * Set the sort definition.
     *
     * @param  array<int|string, mixed>  $sort
     */
    public function sort(array $sort): self
    {
        $this->request['body']['sort'] = $sort;

        return $this;
    }

    /**
     * Set the rescore definition.
     *
     * @param  array<string, mixed>  $rescore
     */
    public function rescore(array $rescore): self
    {
        $this->request['body']['rescore'] = $rescore;

        return $this;
    }

    /**
     * Set the result offset.
     */
    public function from(int $from): self
    {
        $this->request['body']['from'] = $from;

        return $this;
    }

    /**
     * Set the maximum number of results.
     */
    public function size(int $size): self
    {
        $this->request['body']['size'] = $size;

        return $this;
    }

    /**
     * Set the suggest definition.
     *
     * @param  array<string, mixed>  $suggest
     */
    public function suggest(array $suggest): self
    {
        $this->request['body']['suggest'] = $suggest;

        return $this;
    }

    /**
     * Set the source filtering definition.
     *
     * @param  bool|string|array<int|string, mixed>  $source
     */
    public function source(bool|string|array $source): self
    {
        $this->request['body']['_source'] = $source;

        return $this;
    }

    /**
     * Set the field collapse definition.
     *
     * @param  array<string, mixed>  $collapse
     */
    public function collapse(array $collapse): self
    {
        $this->request['body']['collapse'] = $collapse;

        return $this;
    }

    /**
     * Set the aggregation definitions.
     *
     * @param  array<string, mixed>  $aggregations
     */
    public function aggregations(array $aggregations): self
    {
        $this->request['body']['aggregations'] = $aggregations;

        return $this;
    }

    /**
     * Set the post filter definition.
     *
     * @param  array<string, mixed>  $postFilter
     */
    public function postFilter(array $postFilter): self
    {
        $this->request['body']['post_filter'] = $postFilter;

        return $this;
    }

    /**
     * Set total-hit tracking.
     */
    public function trackTotalHits(int|bool $trackTotalHits): self
    {
        $this->request['body']['track_total_hits'] = $trackTotalHits;

        return $this;
    }

    /**
     * Set index boost definitions.
     *
     * @param  array<int, array<string, int|float>>  $indicesBoost
     */
    public function indicesBoost(array $indicesBoost): self
    {
        $this->request['body']['indices_boost'] = $indicesBoost;

        return $this;
    }

    /**
     * Set score tracking.
     */
    public function trackScores(bool $trackScores): self
    {
        $this->request['body']['track_scores'] = $trackScores;

        return $this;
    }

    /**
     * Set the minimum score.
     */
    public function minScore(float $minScore): self
    {
        $this->request['body']['min_score'] = $minScore;

        return $this;
    }

    /**
     * Set script fields.
     *
     * @param  array<string, mixed>  $scriptFields
     */
    public function scriptFields(array $scriptFields): self
    {
        $this->request['body']['script_fields'] = $scriptFields;

        return $this;
    }

    /**
     * Set the OpenSearch search type.
     */
    public function searchType(string $searchType): self
    {
        $this->request['search_type'] = $searchType;

        return $this;
    }

    /**
     * Set the OpenSearch search preference.
     */
    public function preference(string $preference): self
    {
        $this->request['preference'] = $preference;

        return $this;
    }

    /**
     * Get the OpenSearch search request payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->request;
    }
}
