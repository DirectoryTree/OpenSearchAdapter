<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use BadMethodCallException;
use Closure;
use DirectoryTree\OpenSearchAdapter\Support\Str;

/**
 * @see https://docs.opensearch.org/latest/field-types/
 * @see https://docs.opensearch.org/latest/mappings/
 *
 * @method $this alias(string $name, array $parameters = [])
 * @method $this binary(string $name, array $parameters = [])
 * @method $this boolean(string $name, array $parameters = [])
 * @method $this byte(string $name, array $parameters = [])
 * @method $this completion(string $name, array $parameters = [])
 * @method $this constantKeyword(string $name, array $parameters = [])
 * @method $this date(string $name, array $parameters = [])
 * @method $this dateNanos(string $name, array $parameters = [])
 * @method $this dateRange(string $name, array $parameters = [])
 * @method $this denseVector(string $name, array $parameters = [])
 * @method $this double(string $name, array $parameters = [])
 * @method $this doubleRange(string $name, array $parameters = [])
 * @method $this flattened(string $name, array $parameters = [])
 * @method $this float(string $name, array $parameters = [])
 * @method $this floatRange(string $name, array $parameters = [])
 * @method $this geoPoint(string $name, array $parameters = [])
 * @method $this geoShape(string $name, array $parameters = [])
 * @method $this halfFloat(string $name, array $parameters = [])
 * @method $this histogram(string $name)
 * @method $this integer(string $name, array $parameters = [])
 * @method $this integerRange(string $name, array $parameters = [])
 * @method $this ip(string $name, array $parameters = [])
 * @method $this ipRange(string $name, array $parameters = [])
 * @method $this join(string $name, array $parameters = [])
 * @method $this keyword(string $name, array $parameters = [])
 * @method $this long(string $name, array $parameters = [])
 * @method $this longRange(string $name, array $parameters = [])
 * @method $this percolator(string $name)
 * @method $this rankFeature(string $name, array $parameters = [])
 * @method $this rankFeatures(string $name)
 * @method $this scaledFloat(string $name, array $parameters = [])
 * @method $this searchAsYouType(string $name, array $parameters = [])
 * @method $this shape(string $name, array $parameters = [])
 * @method $this short(string $name, array $parameters = [])
 * @method $this sparseVector(string $name)
 * @method $this text(string $name, array $parameters = [])
 * @method $this tokenCount(string $name, array $parameters = [])
 * @method $this wildcard(string $name, array $parameters = [])
 */
class MappingProperties
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
     * @param  Closure|array<string, mixed>  $parameters
     */
    public function object(string $name, Closure|array $parameters = []): self
    {
        $this->properties[$name] = ['type' => 'object'];

        if ($parameters instanceof Closure || ! empty($parameters)) {
            $this->properties[$name] += $this->normalizeParametersWithProperties($parameters);
        }

        return $this;
    }

    /**
     * Add a nested field definition.
     *
     * @param  Closure|array<string, mixed>  $parameters
     */
    public function nested(string $name, Closure|array $parameters = []): self
    {
        $this->properties[$name] = ['type' => 'nested'];

        if ($parameters instanceof Closure || ! empty($parameters)) {
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
