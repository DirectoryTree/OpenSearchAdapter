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
     * Set request-scoped derived fields.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/derived/
     *
     * @param  array<string, mixed>  $derived
     */
    public function derived(array $derived): self
    {
        return $this->body('derived', $derived);
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
     * Set the sliced scroll definition.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/scroll/#using-sliced-scroll
     *
     * @param  array{id: int, max: int}  $slice
     */
    public function slice(array $slice): self
    {
        return $this->body('slice', $slice);
    }

    /**
     * Set the point in time definition.
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
     * Set a temporary search pipeline definition.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/search-pipelines/debugging-search-pipeline/
     *
     * @param  array<string, mixed>  $searchPipeline
     */
    public function temporarySearchPipeline(array $searchPipeline): self
    {
        return $this->body('search_pipeline', $searchPipeline);
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
     * Set named-query score inclusion.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function includeNamedQueriesScore(bool $includeNamedQueriesScore = true): self
    {
        return $this->body('include_named_queries_score', $includeNamedQueriesScore);
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
     * Set search stats groups.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/#search-stats-groups
     *
     * @param  array<int, string>  $stats
     */
    public function stats(array $stats): self
    {
        return $this->body('stats', $stats);
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
     * Set index wildcard behavior when no concrete indices are resolved.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function allowNoIndices(bool $allowNoIndices): self
    {
        return $this->parameter('allow_no_indices', $allowNoIndices);
    }

    /**
     * Set partial result handling.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function allowPartialSearchResults(bool $allowPartialSearchResults): self
    {
        return $this->parameter('allow_partial_search_results', $allowPartialSearchResults);
    }

    /**
     * Set the analyzer for query-string searches.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function analyzer(string $analyzer): self
    {
        return $this->parameter('analyzer', $analyzer);
    }

    /**
     * Set wildcard analysis for query-string searches.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function analyzeWildcard(bool $analyzeWildcard = true): self
    {
        return $this->parameter('analyze_wildcard', $analyzeWildcard);
    }

    /**
     * Set the reduce batch size.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function batchedReduceSize(int $batchedReduceSize): self
    {
        return $this->parameter('batched_reduce_size', $batchedReduceSize);
    }

    /**
     * Set the cancellation time interval.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function cancelAfterTimeInterval(string $cancelAfterTimeInterval): self
    {
        return $this->parameter('cancel_after_time_interval', $cancelAfterTimeInterval);
    }

    /**
     * Set cross-cluster round trip minimization.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function ccsMinimizeRoundtrips(bool $ccsMinimizeRoundtrips): self
    {
        return $this->parameter('ccs_minimize_roundtrips', $ccsMinimizeRoundtrips);
    }

    /**
     * Set the default operator for query-string searches.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function defaultOperator(string $defaultOperator): self
    {
        return $this->parameter('default_operator', $defaultOperator);
    }

    /**
     * Set the default field for query-string searches.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function defaultField(string $defaultField): self
    {
        return $this->parameter('df', $defaultField);
    }

    /**
     * Set wildcard index expansion.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function expandWildcards(string $expandWildcards): self
    {
        return $this->parameter('expand_wildcards', $expandWildcards);
    }

    /**
     * Set throttled index handling.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function ignoreThrottled(bool $ignoreThrottled): self
    {
        return $this->parameter('ignore_throttled', $ignoreThrottled);
    }

    /**
     * Set unavailable index handling.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function ignoreUnavailable(bool $ignoreUnavailable): self
    {
        return $this->parameter('ignore_unavailable', $ignoreUnavailable);
    }

    /**
     * Set lenient mode for query-string searches.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function lenient(bool $lenient = true): self
    {
        return $this->parameter('lenient', $lenient);
    }

    /**
     * Set the maximum concurrent shard requests per node.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function maxConcurrentShardRequests(int $maxConcurrentShardRequests): self
    {
        return $this->parameter('max_concurrent_shard_requests', $maxConcurrentShardRequests);
    }

    /**
     * Set phase-level took reporting.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function phaseTook(bool $phaseTook = true): self
    {
        return $this->parameter('phase_took', $phaseTook);
    }

    /**
     * Set the pre-filter shard threshold.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function preFilterShardSize(int $preFilterShardSize): self
    {
        return $this->parameter('pre_filter_shard_size', $preFilterShardSize);
    }

    /**
     * Set a Lucene query-string query.
     *
     * @see https://docs.opensearch.org/latest/query-dsl/full-text/query-string/
     */
    public function queryString(string $query): self
    {
        return $this->parameter('q', $query);
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
     * Set total-hit rendering as an integer.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function restTotalHitsAsInt(bool $restTotalHitsAsInt = true): self
    {
        return $this->parameter('rest_total_hits_as_int', $restTotalHitsAsInt);
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
     * Set query-string suggestion field.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function suggestField(string $suggestField): self
    {
        return $this->parameter('suggest_field', $suggestField);
    }

    /**
     * Set query-string suggestion mode.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function suggestMode(string $suggestMode): self
    {
        return $this->parameter('suggest_mode', $suggestMode);
    }

    /**
     * Set query-string suggestion size.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function suggestSize(int $suggestSize): self
    {
        return $this->parameter('suggest_size', $suggestSize);
    }

    /**
     * Set query-string suggestion text.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function suggestText(string $suggestText): self
    {
        return $this->parameter('suggest_text', $suggestText);
    }

    /**
     * Set typed key rendering.
     *
     * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
     */
    public function typedKeys(bool $typedKeys = true): self
    {
        return $this->parameter('typed_keys', $typedKeys);
    }

    /**
     * Set verbose search pipeline debugging.
     *
     * @see https://docs.opensearch.org/latest/search-plugins/search-pipelines/debugging-search-pipeline/
     */
    public function verbosePipeline(bool $verbosePipeline = true): self
    {
        return $this->parameter('verbose_pipeline', $verbosePipeline);
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
