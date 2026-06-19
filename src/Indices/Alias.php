<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

/**
 * @see https://docs.opensearch.org/latest/api-reference/index-apis/alias/
 */
class Alias
{
    /**
     * Create a new alias instance.
     *
     * @param  string  $name  The alias name.
     * @param  array<string, mixed>|null  $filter
     * @param  string|null  $routing  The optional alias routing value.
     */
    public function __construct(
        protected string $name,
        protected ?array $filter = null,
        protected ?string $routing = null,
    ) {}

    /**
     * Get the alias name.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the alias filter.
     *
     * @return array<string, mixed>|null
     */
    public function filter(): ?array
    {
        return $this->filter;
    }

    /**
     * Get the alias routing value.
     */
    public function routing(): ?string
    {
        return $this->routing;
    }
}
