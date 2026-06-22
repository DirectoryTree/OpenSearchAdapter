<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

/**
 * @see https://docs.opensearch.org/latest/api-reference/search-apis/search/
 */
class TotalHits implements RawResponseInterface
{
    /**
     * Create a new total hits instance.
     *
     * @param  array<string, mixed>|int  $total
     */
    public function __construct(
        protected array|int $total,
    ) {
        if (is_int($total)) {
            $this->total = [
                'value' => $total,
                'relation' => 'eq',
            ];
        }
    }

    /**
     * Get the total hit count value.
     */
    public function value(): int
    {
        return $this->total['value'];
    }

    /**
     * Get the total hit count relation.
     */
    public function relation(): string
    {
        return $this->total['relation'];
    }

    /**
     * Determine if the total hit count is exact.
     */
    public function isExact(): bool
    {
        return $this->relation() === 'eq';
    }

    /**
     * Get the raw OpenSearch total hits payload.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->total;
    }
}
