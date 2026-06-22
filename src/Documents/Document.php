<?php

namespace DirectoryTree\OpenSearchAdapter\Documents;

/**
 * @see https://docs.opensearch.org/latest/api-reference/document-apis/index-document/
 */
class Document
{
    /**
     * Create a new document instance.
     *
     * @param  string  $id  The OpenSearch document identifier.
     * @param  array<string, mixed>  $source
     */
    public function __construct(
        protected string $id,
        protected array $source,
    ) {}

    /**
     * Get the document identifier.
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Get the full document source.
     *
     * @return array<string, mixed>
     */
    public function source(): array
    {
        return $this->source;
    }

    /**
     * Get a single value from the document source.
     */
    public function get(string $key): mixed
    {
        return $this->source[$key] ?? null;
    }

    /**
     * Get the array representation of the document.
     *
     * @return array{id: string, source: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
        ];
    }
}
