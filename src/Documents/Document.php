<?php

namespace DirectoryTree\OpenSearchAdapter\Documents;

class Document
{
    /**
     * Create a new document instance.
     *
     * @param  string  $id  The OpenSearch document identifier.
     * @param  array<string, mixed>  $content
     */
    public function __construct(
        protected string $id,
        protected array $content,
    ) {}

    /**
     * Get the document identifier.
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Get the full document source or a single value from it.
     */
    public function content(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->content;
        }

        return $this->content[$key] ?? null;
    }

    /**
     * Get the array representation of the document.
     *
     * @return array{id: string, content: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
        ];
    }
}
