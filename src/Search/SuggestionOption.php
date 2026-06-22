<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/search-plugins/searching-data/did-you-mean/
 */
class SuggestionOption implements RawResponseInterface
{
    /**
     * Create a new suggestion option instance.
     *
     * @param  array<string, mixed>  $option
     */
    public function __construct(
        protected array $option,
    ) {}

    /**
     * Get the suggestion option text.
     */
    public function text(): string
    {
        return $this->option['text'];
    }

    /**
     * Get the suggestion option score.
     */
    public function score(): ?float
    {
        return $this->option['score'] ?? null;
    }

    /**
     * Get the highlighted suggestion option text.
     */
    public function highlighted(): ?string
    {
        return $this->option['highlighted'] ?? null;
    }

    /**
     * Determine if the collate query matched for the suggestion option.
     */
    public function collateMatch(): ?bool
    {
        return $this->option['collate_match'] ?? null;
    }

    /**
     * Get the document source for completion suggestion options.
     *
     * @return array<string, mixed>|null
     */
    public function source(): ?array
    {
        return $this->option['_source'] ?? null;
    }

    /**
     * Get the raw OpenSearch suggestion option payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->option;
    }
}
