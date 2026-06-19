<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

class IndexBlueprint
{
    /**
     * Create a new index blueprint instance.
     */
    public function __construct(
        /**
         * The index name.
         */
        protected string $name,

        /**
         * The index mapping definition.
         */
        protected ?Mapping $mapping = null,

        /**
         * The index settings definition.
         */
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
