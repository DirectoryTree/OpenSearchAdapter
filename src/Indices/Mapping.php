<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use Closure;

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
 * @method $this nested(string $name, Closure|array|null $parameters = null)
 * @method $this object(string $name, Closure|array|null $parameters = null)
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
class Mapping
{
    /**
     * Indicates if OpenSearch should index field names.
     */
    protected ?bool $isFieldNamesEnabled = null;

    /**
     * Indicates if OpenSearch should store the document source.
     */
    protected ?bool $isSourceEnabled = null;

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
