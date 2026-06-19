<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use BadMethodCallException;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

/**
 * @method $this alias(string $name, array|null $parameters = null)
 * @method $this binary(string $name, array|null $parameters = null)
 * @method $this boolean(string $name, array|null $parameters = null)
 * @method $this byte(string $name, array|null $parameters = null)
 * @method $this completion(string $name, array|null $parameters = null)
 * @method $this constantKeyword(string $name, array|null $parameters = null)
 * @method $this date(string $name, array|null $parameters = null)
 * @method $this dateNanos(string $name, array|null $parameters = null)
 * @method $this dateRange(string $name, array|null $parameters = null)
 * @method $this denseVector(string $name, array|null $parameters = null)
 * @method $this double(string $name, array|null $parameters = null)
 * @method $this doubleRange(string $name, array|null $parameters = null)
 * @method $this flattened(string $name, array|null $parameters = null)
 * @method $this float(string $name, array|null $parameters = null)
 * @method $this floatRange(string $name, array|null $parameters = null)
 * @method $this geoPoint(string $name, array|null $parameters = null)
 * @method $this geoShape(string $name, array|null $parameters = null)
 * @method $this halfFloat(string $name, array|null $parameters = null)
 * @method $this histogram(string $name)
 * @method $this integer(string $name, array|null $parameters = null)
 * @method $this integerRange(string $name, array|null $parameters = null)
 * @method $this ip(string $name, array|null $parameters = null)
 * @method $this ipRange(string $name, array|null $parameters = null)
 * @method $this join(string $name, array|null $parameters = null)
 * @method $this keyword(string $name, array|null $parameters = null)
 * @method $this long(string $name, array|null $parameters = null)
 * @method $this longRange(string $name, array|null $parameters = null)
 * @method $this percolator(string $name)
 * @method $this rankFeature(string $name, array|null $parameters = null)
 * @method $this rankFeatures(string $name)
 * @method $this scaledFloat(string $name, array|null $parameters = null)
 * @method $this searchAsYouType(string $name, array|null $parameters = null)
 * @method $this shape(string $name, array|null $parameters = null)
 * @method $this short(string $name, array|null $parameters = null)
 * @method $this sparseVector(string $name)
 * @method $this text(string $name, array|null $parameters = null)
 * @method $this tokenCount(string $name, array|null $parameters = null)
 * @method $this wildcard(string $name, array|null $parameters = null)
 */
class MappingProperties implements Arrayable
{
    /**
     * The property definitions.
     *
     * @var array<string, mixed>
     */
    protected array $properties = [];

    /**
     * Add an object field definition.
     *
     * @param  Closure|array<string, mixed>|null  $parameters
     */
    public function object(string $name, Closure|array|null $parameters = null): self
    {
        $this->properties[$name] = ['type' => 'object'];

        if (isset($parameters)) {
            $this->properties[$name] += $this->normalizeParametersWithProperties($parameters);
        }

        return $this;
    }

    /**
     * Add a nested field definition.
     *
     * @param  Closure|array<string, mixed>|null  $parameters
     */
    public function nested(string $name, Closure|array|null $parameters = null): self
    {
        $this->properties[$name] = ['type' => 'nested'];

        if (isset($parameters)) {
            $this->properties[$name] += $this->normalizeParametersWithProperties($parameters);
        }

        return $this;
    }

    /**
     * Add a field definition using a dynamic OpenSearch field type method.
     *
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $method, array $arguments): self
    {
        $argumentsCount = count($arguments);

        if ($argumentsCount === 0 || $argumentsCount > 2) {
            throw new BadMethodCallException(sprintf('Invalid number of arguments for %s method', $method));
        }

        $property = ['type' => Str::snake($method)];

        if (isset($arguments[1])) {
            $property += $arguments[1];
        }

        $this->properties[$arguments[0]] = $property;

        return $this;
    }

    /**
     * Get the OpenSearch properties payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->properties;
    }

    /**
     * Normalize nested property definitions into OpenSearch arrays.
     *
     * @param  Closure|array<string, mixed>  $parameters
     * @return array<string, mixed>
     */
    protected function normalizeParametersWithProperties(Closure|array $parameters): array
    {
        if ($parameters instanceof Closure) {
            $parameters = $parameters(new self);
        }

        if (($parameters['properties'] ?? null) instanceof self) {
            $parameters['properties'] = $parameters['properties']->toArray();
        }

        return $parameters;
    }
}
