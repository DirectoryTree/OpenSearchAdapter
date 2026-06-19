<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use Illuminate\Support\Collection;

class Aggregation implements RawResponseInterface
{
    protected array $aggregation;

    public function __construct(array $aggregation)
    {
        $this->aggregation = $aggregation;
    }

    /**
     * @return Collection|Bucket[]
     */
    public function buckets(): Collection
    {
        $buckets = $this->aggregation['buckets'] ?? [];

        return collect($buckets)->map(static function (array $bucket) {
            return new Bucket($bucket);
        });
    }

    public function raw(): array
    {
        return $this->aggregation;
    }
}
