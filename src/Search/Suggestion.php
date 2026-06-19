<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use Illuminate\Support\Collection;

class Suggestion implements RawResponseInterface
{
    /**
     * Create a new suggestion instance.
     *
     * @param  array<string, mixed>  $suggestion
     */
    public function __construct(
        /**
         * The raw OpenSearch suggestion payload.
         *
         * @var array<string, mixed>
         */
        protected array $suggestion,
    ) {}

    /**
     * Get the suggestion text.
     */
    public function text(): string
    {
        return $this->suggestion['text'];
    }

    /**
     * Get the suggestion offset.
     */
    public function offset(): int
    {
        return $this->suggestion['offset'];
    }

    /**
     * Get the suggestion length.
     */
    public function length(): int
    {
        return $this->suggestion['length'];
    }

    /**
     * Get the suggestion options.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function options(): Collection
    {
        return collect($this->suggestion['options']);
    }

    /**
     * Get the raw OpenSearch suggestion payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->suggestion;
    }
}
