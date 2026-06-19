<?php

namespace DirectoryTree\OpenSearchAdapter\Documents;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Document implements Arrayable
{
    protected string $id;

    protected array $content;

    public function __construct(string $id, array $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function content(?string $key = null): mixed
    {
        return Arr::get($this->content, $key);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
        ];
    }
}
