<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use Closure;

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
 * @method $this nested(string $name, Closure|array $parameters = [])
 * @method $this object(string $name, Closure|array $parameters = [])
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
class Mapping
{
    /**
     * The mapping property definitions.
     */
    protected MappingProperties $properties;

    /**
     * The dynamic template definitions.
     *
     * @var array<int, array<string, mixed>>
     */
    protected array $dynamicTemplates = [];

    /**
     * Indicates if OpenSearch should store the document source.
     */
    protected ?bool $isSourceEnabled = null;

    /**
     * Indicates if OpenSearch should index field names.
     */
    protected ?bool $isFieldNamesEnabled = null;

    /**
     * Create a new mapping instance.
     */
    public function __construct()
    {
        $this->properties = new MappingProperties;
    }

    /**
     * Enable OpenSearch field name indexing.
     */
    public function enableFieldNames(): self
    {
        $this->isFieldNamesEnabled = true;

        return $this;
    }

    /**
     * Disable OpenSearch field name indexing.
     */
    public function disableFieldNames(): self
    {
        $this->isFieldNamesEnabled = false;

        return $this;
    }

    /**
     * Enable OpenSearch source storage.
     */
    public function enableSource(): self
    {
        $this->isSourceEnabled = true;

        return $this;
    }

    /**
     * Disable OpenSearch source storage.
     */
    public function disableSource(): self
    {
        $this->isSourceEnabled = false;

        return $this;
    }

    /**
     * Add a dynamic template definition.
     *
     * @param  array<string, mixed>  $parameters
     */
    public function dynamicTemplate(string $name, array $parameters): self
    {
        $this->dynamicTemplates[] = [$name => $parameters];

        return $this;
    }

    /**
     * Forward dynamic property definitions to the mapping properties builder.
     *
     * @param  array<int, mixed>  $parameters
     */
    public function __call(string $method, array $parameters): self
    {
        $this->properties->{$method}(...$parameters);

        return $this;
    }

    /**
     * Get the OpenSearch mapping payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $mapping = [];

        $properties = $this->properties->toArray();

        if (isset($this->isFieldNamesEnabled)) {
            $mapping['_field_names'] = [
                'enabled' => $this->isFieldNamesEnabled,
            ];
        }

        if (isset($this->isSourceEnabled)) {
            $mapping['_source'] = [
                'enabled' => $this->isSourceEnabled,
            ];
        }

        if (! empty($properties)) {
            $mapping['properties'] = $properties;
        }

        if (! empty($this->dynamicTemplates)) {
            $mapping['dynamic_templates'] = $this->dynamicTemplates;
        }

        return $mapping;
    }
}
