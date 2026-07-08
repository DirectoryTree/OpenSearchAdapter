<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
 */
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
     * @param  array<string, mixed>  $query
     */
    public function __construct(array $query = [])
    {
        if (! empty($query)) {
            $this->query($query);
        }
    }

    /**
     * Set the query definition.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  array<string, mixed>  $query
     */
    public function query(array $query): self
    {
        return $this->body('query', $query);
    }

    /**
     * Set a search request body option.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function body(string $key, mixed $value): self
    {
        $body = $this->request['body'] ?? [];

        $body[$key] = $value;

        return $this->parameter('body', $body);
    }

    /**
     * Set the highlight definition.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/searching-data/highlight/
     *
     * @param  array<string, mixed>  $highlight
     */
    public function highlight(array $highlight): self
    {
        return $this->body('highlight', $highlight);
    }

    /**
     * Set the sort definition.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/searching-data/sort/
     *
     * @param  array<int|string, mixed>  $sort
     */
    public function sort(array $sort): self
    {
        return $this->body('sort', $sort);
    }

    /**
     * Determine if the request has sort clauses.
     */
    public function hasSort(): bool
    {
        return ! empty($this->request['body']['sort'] ?? []);
    }

    /**
     * Set fields to return with optional formatting.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  array<int, mixed>  $fields
     */
    public function fields(array $fields): self
    {
        return $this->body('fields', $fields);
    }

    /**
     * Set fields to return in their doc values representation.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  array<int, mixed>  $docValueFields
     */
    public function docValueFields(array $docValueFields): self
    {
        return $this->body('docvalue_fields', $docValueFields);
    }

    /**
     * Set stored fields to return.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  string|array<int, string>  $storedFields
     */
    public function storedFields(string|array $storedFields): self
    {
        return $this->body('stored_fields', $storedFields);
    }

    /**
     * Set the hit sort values to search after.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/searching-data/paginate/
     *
     * @param  array<int, mixed>  $searchAfter
     */
    public function searchAfter(array $searchAfter): self
    {
        return $this->body('search_after', $searchAfter);
    }

    /**
     * Set the point in time definition for this search request.
     *
     * The keep alive value is optional when searching with an existing PIT.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/point-in-time-api/
     *
     * @param  array{id: string, keep_alive?: string}|string  $pit
     */
    public function pit(array|string $pit, ?string $keepAlive = null): self
    {
        $pit = is_array($pit) ? $pit : ['id' => $pit];

        if (! is_null($keepAlive)) {
            $pit['keep_alive'] = $keepAlive;
        }

        return $this->body('pit', $pit);
    }

    /**
     * Set the rescore definition.
     *
     * @see https://docs.opensearch.org/latest/query-dsl/rescore/
     *
     * @param  array<string, mixed>  $rescore
     */
    public function rescore(array $rescore): self
    {
        return $this->body('rescore', $rescore);
    }

    /**
     * Set explain mode.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function explain(bool $explain = true): self
    {
        return $this->body('explain', $explain);
    }

    /**
     * Set profiling mode.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/profile/
     */
    public function profile(bool $profile = true): self
    {
        return $this->body('profile', $profile);
    }

    /**
     * Set the result offset.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function from(int $from): self
    {
        return $this->body('from', $from);
    }

    /**
     * Set the maximum number of results.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function size(int $size): self
    {
        return $this->body('size', $size);
    }

    /**
     * Set the suggest definition.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/searching-data/did-you-mean/
     *
     * @param  array<string, mixed>  $suggest
     */
    public function suggest(array $suggest): self
    {
        return $this->body('suggest', $suggest);
    }

    /**
     * Set the source filtering definition.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/searching-data/retrieve-specific-fields/
     *
     * @param  bool|string|array<int|string, mixed>  $source
     */
    public function source(bool|string|array $source): self
    {
        return $this->body('_source', $source);
    }

    /**
     * Set source fields to include.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  string|array<int, string>  $sourceIncludes
     */
    public function sourceIncludes(string|array $sourceIncludes): self
    {
        return $this->parameter('_source_includes', $sourceIncludes);
    }

    /**
     * Set source fields to exclude.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  string|array<int, string>  $sourceExcludes
     */
    public function sourceExcludes(string|array $sourceExcludes): self
    {
        return $this->parameter('_source_excludes', $sourceExcludes);
    }

    /**
     * Set the field collapse definition.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/searching-data/collapse-search/
     *
     * @param  array<string, mixed>  $collapse
     */
    public function collapse(array $collapse): self
    {
        return $this->body('collapse', $collapse);
    }

    /**
     * Set aggregation definitions using the OpenSearch short key.
     *
     * @see https://docs.opensearch.org/latest/aggregations/
     *
     * @param  array<string, mixed>  $aggregations
     */
    public function aggs(array $aggregations): self
    {
        return $this->body('aggs', $aggregations);
    }

    /**
     * Set the aggregation definitions.
     *
     * @see https://docs.opensearch.org/latest/aggregations/
     *
     * @param  array<string, mixed>  $aggregations
     */
    public function aggregations(array $aggregations): self
    {
        return $this->body('aggregations', $aggregations);
    }

    /**
     * Set the post filter definition.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  array<string, mixed>  $postFilter
     */
    public function postFilter(array $postFilter): self
    {
        return $this->body('post_filter', $postFilter);
    }

    /**
     * Set total-hit tracking.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function trackTotalHits(int|bool $trackTotalHits): self
    {
        return $this->body('track_total_hits', $trackTotalHits);
    }

    /**
     * Set index boost definitions.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  array<int, array<string, int|float>>  $indicesBoost
     */
    public function indicesBoost(array $indicesBoost): self
    {
        return $this->body('indices_boost', $indicesBoost);
    }

    /**
     * Set sequence number and primary term inclusion.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function seqNoPrimaryTerm(bool $seqNoPrimaryTerm = true): self
    {
        return $this->body('seq_no_primary_term', $seqNoPrimaryTerm);
    }

    /**
     * Set score tracking.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function trackScores(bool $trackScores): self
    {
        return $this->body('track_scores', $trackScores);
    }

    /**
     * Set the minimum score.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function minScore(float $minScore): self
    {
        return $this->body('min_score', $minScore);
    }

    /**
     * Set the maximum number of matching documents to process before terminating.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function terminateAfter(int $terminateAfter): self
    {
        return $this->body('terminate_after', $terminateAfter);
    }

    /**
     * Set the search timeout.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function timeout(string $timeout): self
    {
        return $this->body('timeout', $timeout);
    }

    /**
     * Set document version inclusion.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function version(bool $version = true): self
    {
        return $this->body('version', $version);
    }

    /**
     * Set script fields.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     *
     * @param  array<string, mixed>  $scriptFields
     */
    public function scriptFields(array $scriptFields): self
    {
        return $this->body('script_fields', $scriptFields);
    }

    /**
     * Set search request cache usage.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function requestCache(bool $requestCache): self
    {
        return $this->parameter('request_cache', $requestCache);
    }

    /**
     * Set the routing value.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function routing(string $routing): self
    {
        return $this->parameter('routing', $routing);
    }

    /**
     * Set how long to retain the scroll context.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function scroll(string $scroll): self
    {
        return $this->parameter('scroll', $scroll);
    }

    /**
     * Set an existing search pipeline to run.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/search-pipelines/debugging-search-pipeline/
     */
    public function searchPipeline(string $searchPipeline): self
    {
        return $this->parameter('search_pipeline', $searchPipeline);
    }

    /**
     * Set the OpenSearch search type.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function searchType(string $searchType): self
    {
        return $this->parameter('search_type', $searchType);
    }

    /**
     * Set the OpenSearch search preference.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function preference(string $preference): self
    {
        return $this->parameter('preference', $preference);
    }

    /**
     * Set a search request query parameter.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function parameter(string $key, mixed $value): self
    {
        $this->request[$key] = $value;

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
