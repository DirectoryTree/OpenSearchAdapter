<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/aggregations/
 */
class Aggregation implements RawResponseInterface
{
    /**
     * Create a new aggregation instance.
     *
     * @param  array<string, mixed>  $aggregation
     */
    public function __construct(
        protected array $aggregation,
    ) {}

    /**
     * Get the aggregation buckets.
     *
     * @return array<int, Bucket>
     */
    public function buckets(): array
    {
        $buckets = $this->aggregation['buckets'] ?? [];

        return array_map(static fn (array $bucket) => new Bucket($bucket), $buckets);
    }

    /**
     * Get the raw OpenSearch aggregation payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->aggregation;
    }
}
