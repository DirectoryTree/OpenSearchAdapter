<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

/**
 * @see https://docs.opensearch.org/latest/api-reference/index-apis/create-index/
 */
class IndexBlueprint
{
    /**
     * Create a new index blueprint instance.
     *
     * @param  string  $name  The index name.
     */
    public function __construct(
        protected string $name,
        protected ?Mapping $mapping = null,
        protected ?Settings $settings = null,
    ) {}

    /**
     * Get the index name.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the index mapping definition.
     */
    public function mapping(): ?Mapping
    {
        return $this->mapping;
    }

    /**
     * Get the index settings definition.
     */
    public function settings(): ?Settings
    {
        return $this->settings;
    }
}
