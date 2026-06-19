<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use BadMethodCallException;

/**
 * @see https://docs.opensearch.org/latest/install-and-configure/configuring-opensearch/index-settings/
 *
 * @method $this index(array $configuration)
 * @method $this analysis(array $configuration)
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
     * Set an OpenSearch settings group.
     *
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $method, array $arguments): self
    {
        $argumentsCount = count($arguments);

        if ($argumentsCount === 0 || $argumentsCount > 1) {
            throw new BadMethodCallException(sprintf('Invalid number of arguments for %s method', $method));
        }

        $this->settings[$this->snake($method)] = $arguments[0];

        return $this;
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

    /**
     * Convert a camel-case settings group into snake case.
     */
    protected function snake(string $value): string
    {
        return strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }
}
