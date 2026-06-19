<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

interface RawResponseInterface
{
    /**
     * Get the raw OpenSearch response payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array;
}
