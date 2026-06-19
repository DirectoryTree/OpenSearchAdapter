<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

class Alias
{
    /**
     * Create a new alias instance.
     *
     * @param  array<string, mixed>|null  $filter
     */
    public function __construct(
        /**
         * The alias name.
         */
        protected string $name,

        /**
         * The optional alias filter.
         *
         * @var array<string, mixed>|null
         */
        protected ?array $filter = null,

        /**
         * The optional alias routing value.
         */
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
