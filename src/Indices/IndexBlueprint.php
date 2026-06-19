<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

class IndexBlueprint
{
    /**
     * The index name.
     */
    protected string $name;

    /**
     * The index mapping definition.
     */
    protected ?Mapping $mapping;

    /**
     * The index settings definition.
     */
    protected ?Settings $settings;

    /**
     * Create a new index blueprint instance.
     */
    public function __construct(string $name, ?Mapping $mapping = null, ?Settings $settings = null)
    {
        $this->name = $name;
        $this->mapping = $mapping;
        $this->settings = $settings;
    }

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
