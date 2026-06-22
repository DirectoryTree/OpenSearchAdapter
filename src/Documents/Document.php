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
     * Create a fake document instance.
     *
     * @param  array<string, mixed>  $source
     */
    public static function fake(string $id = '1', array $source = []): static
    {
        return new static($id, $source);
    }

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
     * Get the OpenSearch bulk index operation payload.
     *
     * @return array{0: array{index: array<string, string>}, 1: array<string, mixed>}
     */
    public function toBulkIndex(?string $routing = null): array
    {
        $index = ['_id' => $this->id];

        if (! is_null($routing)) {
            $index['routing'] = $routing;
        }

        return [
            compact('index'),
            $this->source,
        ];
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
