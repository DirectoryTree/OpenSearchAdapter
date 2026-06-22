<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/search-plugins/searching-data/did-you-mean/
 */
class Suggestion implements RawResponseInterface
{
    /**
     * Create a new suggestion instance.
     *
     * @param  array<string, mixed>  $suggestion
     */
    public function __construct(
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
     * @return array<int, SuggestionOption>
     */
    public function options(): array
    {
        return array_map(
            static fn (array $option) => new SuggestionOption($option),
            $this->suggestion['options'],
        );
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
