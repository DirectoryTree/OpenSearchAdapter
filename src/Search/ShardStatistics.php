<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
 */
class ShardStatistics implements RawResponseInterface
{
    /**
     * Create a new shard statistics instance.
     *
     * @param  array<string, mixed>  $shards
     */
    public function __construct(
        protected array $shards,
    ) {}

    /**
     * Get the total number of shards searched.
     */
    public function total(): int
    {
        return $this->shards['total'];
    }

    /**
     * Get the number of shards that searched successfully.
     */
    public function successful(): int
    {
        return $this->shards['successful'];
    }

    /**
     * Get the number of shards skipped during the search.
     */
    public function skipped(): int
    {
        return $this->shards['skipped'] ?? 0;
    }

    /**
     * Get the number of shards that failed during the search.
     */
    public function failed(): int
    {
        return $this->shards['failed'];
    }

    /**
     * Get the shard failures.
     *
     * @return array<int, array<string, mixed>>
     */
    public function failures(): array
    {
        return $this->shards['failures'] ?? [];
    }

    /**
     * Get the raw OpenSearch shard statistics payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->shards;
    }
}
