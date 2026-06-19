<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use BadMethodCallException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

/**
 * @method $this index(array $configuration)
 * @method $this analysis(array $configuration)
 */
class Settings implements Arrayable
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

        $this->settings[Str::snake($method)] = $arguments[0];

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
}
