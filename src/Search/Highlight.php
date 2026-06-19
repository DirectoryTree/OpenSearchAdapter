<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use Illuminate\Support\Collection;

class Highlight implements RawResponseInterface
{
    /**
     * The raw OpenSearch highlight payload.
     *
     * @var array<string, array<int, string>>
     */
    protected array $highlight;

    /**
     * Create a new highlight instance.
     *
     * @param  array<string, array<int, string>>  $highlight
     */
    public function __construct(array $highlight)
    {
        $this->highlight = $highlight;
    }

    /**
     * Get highlighted snippets for the given field.
     *
     * @return Collection<int, string>
     */
    public function snippets(string $field): Collection
    {
        return collect($this->highlight[$field] ?? []);
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
