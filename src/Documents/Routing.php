<?php

namespace DirectoryTree\OpenSearchAdapter\Documents;

/**
 * @see https://docs.opensearch.org/latest/api-reference/search-apis/search-shards/
 */
class Routing
{
    /**
     * The routing values keyed by document ID.
     *
     * @var array<string, string>
     */
    protected array $routing = [];

    /**
     * Add a routing value for the given document ID.
     */
    public function add(string $documentId, string $value): self
    {
        $this->routing[$documentId] = $value;

        return $this;
    }

    /**
     * Determine if routing exists for the given document ID.
     */
    public function has(string $documentId): bool
    {
        return isset($this->routing[$documentId]);
    }

    /**
     * Get the routing value for the given document ID.
     */
    public function get(string $documentId): ?string
    {
        return $this->routing[$documentId] ?? null;
    }
}
