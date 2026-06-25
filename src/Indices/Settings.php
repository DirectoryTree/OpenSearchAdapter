<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

/**
 * @see https://docs.opensearch.org/latest/install-and-configure/configuring-opensearch/index-settings/
 */
class Settings
{
    /**
     * The index settings payload.
     *
     * @var array<string, mixed>
     */
    protected array $settings = [];

    /**
     * Set a top-level OpenSearch settings group.
     *
     * @param  array<string, mixed>  $configuration
     */
    public function set(string $group, array $configuration): self
    {
        $this->settings[$group] = $configuration;

        return $this;
    }

    /**
     * Set index-level OpenSearch settings.
     *
     * @see https://docs.opensearch.org/latest/install-and-configure/configuring-opensearch/index-settings/
     *
     * @param  array<string, mixed>  $configuration
     */
    public function index(array $configuration): self
    {
        return $this->set('index', $configuration);
    }

    /**
     * Set index analysis settings.
     *
     * @see https://docs.opensearch.org/latest/analyzers/
     *
     * @param  array<string, mixed>  $configuration
     */
    public function analysis(array $configuration): self
    {
        return $this->set('analysis', $configuration);
    }

    /**
     * Set index similarity settings.
     *
     * @see https://docs.opensearch.org/latest/im-plugin/similarity/
     *
     * @param  array<string, mixed>  $configuration
     */
    public function similarity(array $configuration): self
    {
        return $this->set('similarity', $configuration);
    }

    /**
     * Get the OpenSearch settings payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->settings;
    }
}
