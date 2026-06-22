<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

/**
 * Manages OpenSearch indices, mappings, settings, and aliases.
 */
interface IndexManagerInterface
{
    /**
     * Open the given index.
     */
    public function open(string $index): static;

    /**
     * Close the given index.
     */
    public function close(string $index): static;

    /**
     * Determine if the given index exists.
     */
    public function exists(string $index): bool;

    /**
     * Create an index from the given blueprint.
     */
    public function create(IndexBlueprint $index): static;

    /**
     * Update the mapping for the given index.
     */
    public function putMapping(string $index, Mapping $mapping): static;

    /**
     * Update the settings for the given index.
     */
    public function putSettings(string $index, Settings $settings): static;

    /**
     * Delete the given index.
     */
    public function delete(string $index): static;

    /**
     * Get the aliases for the given index.
     *
     * @return array<string, Alias>
     */
    public function getAliases(string $index): array;

    /**
     * Create or update an alias for the given index.
     */
    public function putAlias(string $index, Alias $alias): static;

    /**
     * Delete the given alias from the index.
     */
    public function deleteAlias(string $index, string $aliasName): static;
}
