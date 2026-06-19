<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use Illuminate\Support\Collection;

class Hit implements RawResponseInterface
{
    /**
     * Create a new hit instance.
     *
     * @param  array<string, mixed>  $hit
     */
    public function __construct(
        /**
         * The raw OpenSearch hit payload.
         *
         * @var array<string, mixed>
         */
        protected array $hit,
    ) {}

    /**
     * Get the index name for the hit.
     */
    public function indexName(): string
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
     * Get the document for the hit.
     */
    public function document(): Document
    {
        return new Document(
            $this->hit['_id'],
            $this->hit['_source'] ?? []
        );
    }

    /**
     * Get the highlight for the hit.
     */
    public function highlight(): ?Highlight
    {
        return isset($this->hit['highlight']) ?
            new Highlight($this->hit['highlight']) : null;
    }

    /**
     * Get the inner hits grouped by relationship name.
     *
     * @return Collection<string, Collection<int, self>>
     */
    public function innerHits(): Collection
    {
        $innerHits = $this->hit['inner_hits'] ?? [];

        return collect($innerHits)->map(static function (array $hits) {
            return collect($hits['hits']['hits'])->map(static function (array $hit) {
                return new self($hit);
            });
        });
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
