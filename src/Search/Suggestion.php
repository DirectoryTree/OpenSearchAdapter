<?php

namespace DirectoryTree\OpenSearchAdapter\Search;

use Illuminate\Support\Collection;

class Suggestion implements RawResponseInterface
{
    protected array $suggestion;

    public function __construct(array $suggestion)
    {
        $this->suggestion = $suggestion;
    }

    public function text(): string
    {
        return $this->suggestion['text'];
    }

    public function offset(): int
    {
        return $this->suggestion['offset'];
    }

    public function length(): int
    {
        return $this->suggestion['length'];
    }

    public function options(): Collection
    {
        return collect($this->suggestion['options']);
    }

    public function raw(): array
    {
        return $this->suggestion;
    }
}
