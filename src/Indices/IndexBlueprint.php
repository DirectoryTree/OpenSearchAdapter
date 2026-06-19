<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

class IndexBlueprint
{
    protected string $name;

    protected ?Mapping $mapping;

    protected ?Settings $settings;

    public function __construct(string $name, ?Mapping $mapping = null, ?Settings $settings = null)
    {
        $this->name = $name;
        $this->mapping = $mapping;
        $this->settings = $settings;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function mapping(): ?Mapping
    {
        return $this->mapping;
    }

    public function settings(): ?Settings
    {
        return $this->settings;
    }
}
