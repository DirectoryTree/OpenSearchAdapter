<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use Illuminate\Support\Collection;
use OpenSearch\Client;
use OpenSearch\Namespaces\IndicesNamespace;

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
        $params = [
            'index' => $index->name(),
        ];

        $mapping = $index->mapping() === null ? [] : $index->mapping()->toArray();
        $settings = $index->settings() === null ? [] : $index->settings()->toArray();

        if (! empty($mapping)) {
            $params['body']['mappings'] = $mapping;
        }

        if (! empty($settings)) {
            $params['body']['settings'] = $settings;
        }

        $this->indices->create($params);

        return $this;
    }

    /**
     * Create an index using raw mapping and settings arrays.
     *
     * @param  array<string, mixed>|null  $mapping
     * @param  array<string, mixed>|null  $settings
     */
    public function createRaw(string $indexName, ?array $mapping = null, ?array $settings = null): self
    {
        $params = [
            'index' => $indexName,
        ];

        if (isset($mapping)) {
            $params['body']['mappings'] = $mapping;
        }

        if (isset($settings)) {
            $params['body']['settings'] = $settings;
        }

        $this->indices->create($params);

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
     * Update the mapping for the given index using a raw array.
     *
     * @param  array<string, mixed>  $mapping
     */
    public function putMappingRaw(string $indexName, array $mapping): self
    {
        $this->indices->putMapping([
            'index' => $indexName,
            'body' => $mapping,
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
     * Update the settings for the given index using a raw array.
     *
     * @param  array<string, mixed>  $settings
     */
    public function putSettingsRaw(string $indexName, array $settings): self
    {
        $this->indices->putSettings([
            'index' => $indexName,
            'body' => [
                'settings' => $settings,
            ],
        ]);

        return $this;
    }

    /**
     * Delete the given index.
     */
    public function drop(string $indexName): self
    {
        $this->indices->delete([
            'index' => $indexName,
        ]);

        return $this;
    }

    /**
     * Get the aliases for the given index.
     *
     * @return Collection<string, Alias>
     */
    public function getAliases(string $indexName): Collection
    {
        $response = $this->indices->getAlias([
            'index' => $indexName,
        ]);

        $aliases = $response[$indexName]['aliases'] ?? [];

        return collect($aliases)->map(static function (array $parameters, string $name) {
            return new Alias(
                $name,
                $parameters['filter'] ?? null,
                $parameters['routing'] ?? null
            );
        });
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

        if ($alias->routing()) {
            $params['body']['routing'] = $alias->routing();
        }

        if ($alias->filter()) {
            $params['body']['filter'] = $alias->filter();
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
