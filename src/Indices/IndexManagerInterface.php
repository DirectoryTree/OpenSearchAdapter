<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

/**
 * Manages OpenSearch indices, mappings, settings, and aliases.
 */
interface IndexManagerInterface
{
    /**
     * Open the given index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/open-index/
     */
    public function open(string $index): static;

    /**
     * Close the given index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/close-index/
     */
    public function close(string $index): static;

    /**
     * Determine if the given index exists.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/exists/
     */
    public function exists(string $index): bool;

    /**
     * Create an index from the given blueprint.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/create-index/
     */
    public function create(IndexBlueprint $index): static;

    /**
     * Update the mapping for the given index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/put-mapping/
     */
    public function putMapping(string $index, Mapping $mapping): static;

    /**
     * Update the settings for the given index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/update-settings/
     */
    public function putSettings(string $index, Settings $settings): static;

    /**
     * Delete the given index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/delete-index/
     */
    public function delete(string $index): static;

    /**
     * Get the aliases for the given index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/alias/
     *
     * @return array<string, Alias>
     */
    public function getAliases(string $index): array;

    /**
     * Create or update an alias for the given index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/alias/
     */
    public function putAlias(string $index, Alias $alias): static;

    /**
     * Delete the given alias from the index.
     *
     * @see https://docs.opensearch.org/latest/api-reference/index-apis/alias/
     */
    public function deleteAlias(string $index, string $aliasName): static;
}
