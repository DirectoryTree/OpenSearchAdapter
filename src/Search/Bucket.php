<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

class Bucket implements RawResponseInterface
{
    protected array $bucket;

    public function __construct(array $bucket)
    {
        $this->bucket = $bucket;
    }

    public function docCount(): int
    {
        return $this->bucket['doc_count'] ?? 0;
    }

    public function key(): mixed
    {
        return $this->bucket['key'];
    }

    public function raw(): array
    {
        return $this->bucket;
    }
}
