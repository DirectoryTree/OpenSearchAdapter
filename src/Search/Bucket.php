<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

class Bucket implements RawResponseInterface
{
    /**
     * Create a new bucket instance.
     *
     * @param  array<string, mixed>  $bucket
     */
    public function __construct(
        /**
         * The raw OpenSearch bucket payload.
         *
         * @var array<string, mixed>
         */
        protected array $bucket,
    ) {}

    /**
     * Get the number of documents in the bucket.
     */
    public function docCount(): int
    {
        return $this->bucket['doc_count'] ?? 0;
    }

    /**
     * Get the bucket key.
     */
    public function key(): mixed
    {
        return $this->bucket['key'];
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
