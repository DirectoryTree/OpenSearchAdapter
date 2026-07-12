<?php

namespace DirectoryTree\OpenSearchAdapter\Testing\Fakes;

use DirectoryTree\OpenSearchAdapter\Indices\Alias;
use DirectoryTree\OpenSearchAdapter\Indices\AliasActions;
use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\IndexManagerInterface;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * Fakes OpenSearch index operations for tests.
 */
class FakeIndexManager implements IndexManagerInterface
{
    /**
     * The opened index names.
     *
     * @var array<int, string>
     */
    protected array $opened = [];

    /**
     * The closed index names.
     *
     * @var array<int, string>
     */
    protected array $closed = [];

    /**
     * The checked index names.
     *
     * @var array<int, string>
     */
    protected array $checked = [];

    /**
     * The created index blueprints.
     *
     * @var array<int, IndexBlueprint>
     */
    protected array $created = [];

    /**
     * The updated index mappings.
     *
     * @var array<int, array{index: string, mapping: Mapping}>
     */
    protected array $mappings = [];

    /**
     * The updated index settings.
     *
     * @var array<int, array{index: string, settings: Settings}>
     */
    protected array $settings = [];

    /**
     * The deleted index names.
     *
     * @var array<int, string>
     */
    protected array $deleted = [];

    /**
     * The aliases put on indices.
     *
     * @var array<int, array{index: string, alias: Alias}>
     */
    protected array $aliases = [];

    /**
     * The atomic alias updates.
     *
     * @var array<int, AliasActions>
     */
    protected array $aliasUpdates = [];

    /**
     * The deleted aliases.
     *
     * @var array<int, array{index: string, alias: string}>
     */
    protected array $deletedAliases = [];

    /**
     * Create a new fake index manager instance.
     *
     * @param  array<int, string>  $existing
     */
    public function __construct(
        protected array $existing = [],
    ) {}

    /**
     * Open the given index.
     */
    public function open(string $index): static
    {
        $this->opened[] = $index;

        return $this;
    }

    /**
     * Close the given index.
     */
    public function close(string $index): static
    {
        $this->closed[] = $index;

        return $this;
    }

    /**
     * Determine if the given index exists.
     */
    public function exists(string $index): bool
    {
        $this->checked[] = $index;

        return in_array($index, $this->existing, true);
    }

    /**
     * Create an index from the given blueprint.
     */
    public function create(IndexBlueprint $index): static
    {
        $this->created[] = $index;
        $this->existing[] = $index->name();

        return $this;
    }

    /**
     * Update the mapping for the given index.
     */
    public function putMapping(string $index, Mapping $mapping): static
    {
        $this->mappings[] = compact('index', 'mapping');

        return $this;
    }

    /**
     * Update the settings for the given index.
     */
    public function putSettings(string $index, Settings $settings): static
    {
        $this->settings[] = compact('index', 'settings');

        return $this;
    }

    /**
     * Delete the given index.
     */
    public function delete(string $index): static
    {
        $this->deleted[] = $index;

        $this->existing = array_values(
            array_diff($this->existing, [$index])
        );

        $this->aliases = array_values(array_filter(
            $this->aliases,
            fn (array $operation) => $operation['index'] !== $index,
        ));

        return $this;
    }

    /**
     * Get the aliases for the given index.
     *
     * @return array<string, Alias>
     */
    public function getAliases(string $index): array
    {
        $aliases = [];

        foreach ($this->aliases as $operation) {
            if ($operation['index'] === $index) {
                $aliases[$operation['alias']->name()] = $operation['alias'];
            }
        }

        return $aliases;
    }

    /**
     * Create or update an alias for the given index.
     */
    public function putAlias(string $index, Alias $alias): static
    {
        $this->removeAlias($index, $alias->name());

        $this->aliases[] = compact('index', 'alias');

        return $this;
    }

    /**
     * Delete the given alias from the index.
     */
    public function deleteAlias(string $index, string $aliasName): static
    {
        $this->deletedAliases[] = [
            'index' => $index,
            'alias' => $aliasName,
        ];

        $this->removeAlias($index, $aliasName);

        return $this;
    }

    /**
     * Atomically apply multiple alias actions.
     */
    public function updateAliases(AliasActions $actions): static
    {
        $this->aliasUpdates[] = $actions;

        foreach ($actions->actions() as $action) {
            if ($parameters = $action['add'] ?? null) {
                $alias = new Alias(
                    $parameters['alias'],
                    $parameters['filter'] ?? null,
                    $parameters['routing'] ?? null,
                    $parameters['is_write_index'] ?? null,
                );

                $this->removeAlias($parameters['index'], $parameters['alias']);

                $this->aliases[] = [
                    'index' => $parameters['index'],
                    'alias' => $alias,
                ];
            }

            if ($parameters = $action['remove'] ?? null) {
                $this->deletedAliases[] = [
                    'index' => $parameters['index'],
                    'alias' => $parameters['alias'],
                ];

                $this->removeAlias($parameters['index'], $parameters['alias']);
            }

            if ($parameters = $action['remove_index'] ?? null) {
                $this->delete($parameters['index']);
            }
        }

        return $this;
    }

    /**
     * Assert that the given index was checked.
     */
    public function assertChecked(string $index): static
    {
        PHPUnit::assertContains($index, $this->checked);

        return $this;
    }

    /**
     * Assert that the given index was created.
     */
    public function assertCreated(IndexBlueprint $index): static
    {
        PHPUnit::assertContainsEquals($index, $this->created);

        return $this;
    }

    /**
     * Assert that the given index was not created.
     */
    public function assertNotCreated(string $index): static
    {
        foreach ($this->created as $created) {
            PHPUnit::assertNotSame($index, $created->name());
        }

        return $this;
    }

    /**
     * Assert that the given index mapping was updated.
     */
    public function assertMappingPut(string $index, Mapping $mapping): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'mapping'), $this->mappings);

        return $this;
    }

    /**
     * Assert that the given index settings were updated.
     */
    public function assertSettingsPut(string $index, Settings $settings): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'settings'), $this->settings);

        return $this;
    }

    /**
     * Assert that the given index was opened.
     */
    public function assertOpened(string $index): static
    {
        PHPUnit::assertContains($index, $this->opened);

        return $this;
    }

    /**
     * Assert that the given index was closed.
     */
    public function assertClosed(string $index): static
    {
        PHPUnit::assertContains($index, $this->closed);

        return $this;
    }

    /**
     * Assert that the given index was deleted.
     */
    public function assertDeleted(string $index): static
    {
        PHPUnit::assertContains($index, $this->deleted);

        return $this;
    }

    /**
     * Assert that the given alias was put on an index.
     */
    public function assertAliasPut(string $index, Alias $alias): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'alias'), $this->aliases);

        return $this;
    }

    /**
     * Assert that the given alias was deleted from an index.
     */
    public function assertAliasDeleted(string $index, string $alias): static
    {
        PHPUnit::assertContainsEquals(compact('index', 'alias'), $this->deletedAliases);

        return $this;
    }

    /**
     * Assert that the given atomic alias update was performed.
     */
    public function assertAliasesUpdated(AliasActions $actions): static
    {
        PHPUnit::assertContainsEquals($actions, $this->aliasUpdates);

        return $this;
    }

    /**
     * Remove an alias from the fake's current state.
     */
    protected function removeAlias(string $index, string $alias): void
    {
        $this->aliases = array_values(
            array_filter($this->aliases, fn (array $operation) => (
                $operation['index'] !== $index || $operation['alias']->name() !== $alias
            ))
        );
    }
}
