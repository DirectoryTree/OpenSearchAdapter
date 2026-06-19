<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use Illuminate\Support\Collection;

class Aggregation implements RawResponseInterface
{
    /**
     * Create a new aggregation instance.
     *
     * @param  array<string, mixed>  $aggregation
     */
    public function __construct(
        /**
         * The raw OpenSearch aggregation payload.
         *
         * @var array<string, mixed>
         */
        protected array $aggregation,
    ) {}

    /**
     * Get the aggregation buckets.
     *
     * @return Collection<int, Bucket>
     */
    public function buckets(): Collection
    {
        $buckets = $this->aggregation['buckets'] ?? [];

        return collect($buckets)->map(static function (array $bucket) {
            return new Bucket($bucket);
        });
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
