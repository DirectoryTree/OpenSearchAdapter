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
class IndexManager
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
    public function open(string $indexName): self
    {
        $this->indices->open([
            'index' => $indexName,
        ]);

        return $this;
    }

    /**
     * Close the given index.
     */
    public function close(string $indexName): self
    {
        $this->indices->close([
            'index' => $indexName,
        ]);

        return $this;
    }

    /**
     * Determine if the given index exists.
     */
    public function exists(string $indexName): bool
    {
        return $this->indices->exists([
            'index' => $indexName,
        ]);
    }

    /**
     * Create an index from the given blueprint.
     */
    public function create(IndexBlueprint $index): self
    {
        $this->indices->create($index->toArray());

        return $this;
    }

    /**
     * Update the mapping for the given index.
     */
    public function putMapping(string $indexName, Mapping $mapping): self
    {
        $this->indices->putMapping([
            'index' => $indexName,
            'body' => $mapping->toArray(),
        ]);

        return $this;
    }

    /**
     * Update the settings for the given index.
     */
    public function putSettings(string $indexName, Settings $settings): self
    {
        $this->indices->putSettings([
            'index' => $indexName,
            'body' => [
                'settings' => $settings->toArray(),
            ],
        ]);

        return $this;
    }

    /**
     * Delete the given index.
     */
    public function delete(string $indexName): self
    {
        $this->indices->delete([
            'index' => $indexName,
        ]);

        return $this;
    }

    /**
     * Get the aliases for the given index.
     *
     * @return array<string, Alias>
     */
    public function getAliases(string $indexName): array
    {
        $response = $this->indices->getAlias([
            'index' => $indexName,
        ]);

        $aliases = $response[$indexName]['aliases'] ?? [];

        $results = [];

        foreach ($aliases as $name => $parameters) {
            $results[$name] = new Alias(
                $name,
                $parameters['filter'] ?? null,
                $parameters['routing'] ?? null
            );
        }

        return $results;
    }

    /**
     * Create or update an alias for the given index.
     */
    public function putAlias(string $indexName, Alias $alias): self
    {
        $params = [
            'index' => $indexName,
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
    public function deleteAlias(string $indexName, string $aliasName): self
    {
        $this->indices->deleteAlias([
            'index' => $indexName,
            'name' => $aliasName,
        ]);

        return $this;
    }
}
