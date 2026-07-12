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
     * @param  bool|null  $isWriteIndex  Whether this is the alias write index.
     */
    public function __construct(
        protected string $name,
        protected ?array $filter = null,
        protected ?string $routing = null,
        protected ?bool $isWriteIndex = null,
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

    /**
     * Determine whether this is the alias write index.
     */
    public function isWriteIndex(): ?bool
    {
        return $this->isWriteIndex;
    }

    /**
     * Get the OpenSearch alias body payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $body = [];

        if ($this->routing) {
            $body['routing'] = $this->routing;
        }

        if ($this->filter) {
            $body['filter'] = $this->filter;
        }

        if (! is_null($this->isWriteIndex)) {
            $body['is_write_index'] = $this->isWriteIndex;
        }

        return $body;
    }
}
