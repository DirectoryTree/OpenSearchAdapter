<?php

namespace DirectoryTree\OpenSearchAdapter\Documents;

/**
 * @see https://docs.opensearch.org/latest/api-reference/document-apis/bulk/
 */
class DocumentRouting
{
    /**
     * The routing values keyed by document ID.
     *
     * @var array<string, string>
     */
    protected array $routes = [];

    /**
     * Create a new routing instance for the given document ID.
     */
    public static function make(string $id, string $value): self
    {
        return (new self)->add($id, $value);
    }

    /**
     * Add a routing value for the given document ID.
     */
    public function add(string $id, string $value): self
    {
        $this->routes[$id] = $value;

        return $this;
    }

    /**
     * Determine if routing exists for the given document ID.
     */
    public function has(string $id): bool
    {
        return isset($this->routes[$id]);
    }

    /**
     * Get the routing value for the given document ID.
     */
    public function get(string $id): ?string
    {
        return $this->routes[$id] ?? null;
    }

    /**
     * Get the document routing values.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->routes;
    }
}
