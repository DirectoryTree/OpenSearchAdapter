<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

class Highlight implements RawResponseInterface
{
    /**
     * Create a new highlight instance.
     *
     * @param  array<string, array<int, string>>  $highlight
     */
    public function __construct(
        protected array $highlight,
    ) {}

    /**
     * Get highlighted snippets for the given field.
     *
     * @return array<int, string>
     */
    public function snippets(string $field): array
    {
        return $this->highlight[$field] ?? [];
    }

    /**
     * Get the raw OpenSearch highlight payload.
     *
     * @return array<string, array<int, string>>
     */
    public function raw(): array
    {
        return $this->highlight;
    }
}
