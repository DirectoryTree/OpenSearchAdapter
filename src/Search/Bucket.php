<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/aggregations/
 */
class Bucket implements RawResponseInterface
{
    /**
     * Create a new bucket instance.
     *
     * @param  array<string, mixed>  $bucket
     */
    public function __construct(
        protected array $bucket,
    ) {}

    /**
     * Get the bucket key.
     */
    public function key(): mixed
    {
        return $this->bucket['key'];
    }

    /**
     * Get the number of documents in the bucket.
     */
    public function docCount(): int
    {
        return $this->bucket['doc_count'] ?? 0;
    }

    /**
     * Get the raw OpenSearch bucket payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->bucket;
    }
}
