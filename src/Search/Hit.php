<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use DirectoryTree\OpenSearchAdapter\Documents\Document;

/**
 * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
 */
class Hit implements RawResponseInterface
{
    /**
     * Create a new hit instance.
     *
     * @param  array<string, mixed>  $hit
     */
    public function __construct(
        protected array $hit,
    ) {}

    /**
     * Get the index name for the hit.
     */
    public function index(): string
    {
        return $this->hit['_index'];
    }

    /**
     * Get the score for the hit.
     */
    public function score(): ?float
    {
        return $this->hit['_score'];
    }

    /**
     * Get the document identifier for the hit.
     */
    public function id(): string
    {
        return $this->hit['_id'];
    }

    /**
     * Get the document source for the hit.
     *
     * @return array<string, mixed>
     */
    public function source(): array
    {
        return $this->hit['_source'] ?? [];
    }

    /**
     * Get the document for the hit.
     */
    public function document(): Document
    {
        return new Document($this->id(), $this->source());
    }

    /**
     * Get the returned document fields.
     *
     * @return array<string, mixed>
     */
    public function fields(): array
    {
        return $this->hit['fields'] ?? [];
    }

    /**
     * Get the hit sort values.
     *
     * @return array<int, mixed>
     */
    public function sort(): array
    {
        return $this->hit['sort'] ?? [];
    }

    /**
     * Get the matched query names.
     *
     * @return array<int, string>
     */
    public function matchedQueries(): array
    {
        return $this->hit['matched_queries'] ?? [];
    }

    /**
     * Get the score explanation.
     *
     * @return array<string, mixed>|null
     */
    public function explanation(): ?array
    {
        return $this->hit['_explanation'] ?? null;
    }

    /**
     * Get the highlight for the hit.
     */
    public function highlight(): ?Highlight
    {
        if (isset($this->hit['highlight'])) {
            return new Highlight($this->hit['highlight']);
        }

        return null;
    }

    /**
     * Get the inner hits grouped by relationship name.
     *
     * @return array<string, array<int, self>>
     */
    public function innerHits(): array
    {
        return array_map(fn (array $hits) => array_map(
            fn (array $hit) => new self($hit),
            $hits['hits']['hits'],
        ), $this->hit['inner_hits'] ?? []);
    }

    /**
     * Get the raw OpenSearch hit payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->hit;
    }
}
