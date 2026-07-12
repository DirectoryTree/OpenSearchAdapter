<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

/**
 * Builds an atomic OpenSearch alias update payload.
 *
 * @see https://docs.opensearch.org/latest/api-reference/alias/aliases-api/
 */
class AliasActions
{
    /**
     * The alias actions.
     *
     * @var array<int, array<string, array<string, mixed>>>
     */
    protected array $actions = [];

    /**
     * Add an alias to an index.
     */
    public function add(string $index, Alias $alias): static
    {
        $this->actions[] = [
            'add' => [
                'index' => $index,
                'alias' => $alias->name(),
                ...$alias->toArray(),
            ],
        ];

        return $this;
    }

    /**
     * Remove an alias from an index.
     */
    public function remove(string $index, string $alias): static
    {
        $this->actions[] = [
            'remove' => compact('index', 'alias'),
        ];

        return $this;
    }

    /**
     * Remove an index as part of the atomic alias update.
     */
    public function removeIndex(string $index): static
    {
        $this->actions[] = [
            'remove_index' => compact('index'),
        ];

        return $this;
    }

    /**
     * Get the configured alias actions.
     *
     * @return array<int, array<string, array<string, mixed>>>
     */
    public function actions(): array
    {
        return $this->actions;
    }

    /**
     * Get the OpenSearch update aliases body payload.
     *
     * @return array{actions: array<int, array<string, array<string, mixed>>>}
     */
    public function toArray(): array
    {
        return ['actions' => $this->actions];
    }
}
