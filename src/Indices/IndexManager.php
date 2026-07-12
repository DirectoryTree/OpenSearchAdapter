<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use OpenSearch\Client;
use OpenSearch\Namespaces\IndicesNamespace;

/**
 * @see https://docs.opensearch.org/latest/api-reference/index-apis/create-index/
 * @see https://docs.opensearch.org/latest/api-reference/index-apis/put-mapping/
 * @see https://docs.opensearch.org/latest/api-reference/index-apis/update-settings/
 * @see https://docs.opensearch.org/latest/api-reference/index-apis/alias/
 */
class IndexManager implements IndexManagerInterface
{
    /**
     * The OpenSearch indices namespace.
     */
    protected IndicesNamespace $indices;

    /**
     * Create a new index manager instance.
     */
    public function __construct(Client $client)
    {
        $this->indices = $client->indices();
    }

    /**
     * Open the given index.
     */
    public function open(string $index): static
    {
        $this->indices->open([
            'index' => $index,
        ]);

        return $this;
    }

    /**
     * Close the given index.
     */
    public function close(string $index): static
    {
        $this->indices->close([
            'index' => $index,
        ]);

        return $this;
    }

    /**
     * Determine if the given index exists.
     */
    public function exists(string $index): bool
    {
        return $this->indices->exists([
            'index' => $index,
        ]);
    }

    /**
     * Create an index from the given blueprint.
     */
    public function create(IndexBlueprint $index): static
    {
        $this->indices->create($index->toArray());

        return $this;
    }

    /**
     * Update the mapping for the given index.
     */
    public function putMapping(string $index, Mapping $mapping): static
    {
        $this->indices->putMapping([
            'index' => $index,
            'body' => $mapping->toArray(),
        ]);

        return $this;
    }

    /**
     * Update the settings for the given index.
     */
    public function putSettings(string $index, Settings $settings): static
    {
        $this->indices->putSettings([
            'index' => $index,
            'body' => [
                'settings' => $settings->toArray(),
            ],
        ]);

        return $this;
    }

    /**
     * Delete the given index.
     */
    public function delete(string $index): static
    {
        $this->indices->delete([
            'index' => $index,
        ]);

        return $this;
    }

    /**
     * Get the aliases for the given index.
     *
     * @return array<string, Alias>
     */
    public function getAliases(string $index): array
    {
        $response = $this->indices->getAlias([
            'index' => $index,
        ]);

        $aliases = $response[$index]['aliases'] ?? [];

        $results = [];

        foreach ($aliases as $name => $parameters) {
            $results[$name] = new Alias(
                $name,
                $parameters['filter'] ?? null,
                $parameters['routing'] ?? null,
                $parameters['is_write_index'] ?? null,
            );
        }

        return $results;
    }

    /**
     * Create or update an alias for the given index.
     */
    public function putAlias(string $index, Alias $alias): static
    {
        $params = [
            'index' => $index,
            'name' => $alias->name(),
        ];

        if ($body = $alias->toArray()) {
            $params['body'] = $body;
        }

        $this->indices->putAlias($params);

        return $this;
    }

    /**
     * Delete the given alias from the index.
     */
    public function deleteAlias(string $index, string $aliasName): static
    {
        $this->indices->deleteAlias([
            'index' => $index,
            'name' => $aliasName,
        ]);

        return $this;
    }

    /**
     * Atomically apply multiple alias actions.
     */
    public function updateAliases(AliasActions $actions): static
    {
        $this->indices->updateAliases([
            'body' => $actions->toArray(),
        ]);

        return $this;
    }
}
