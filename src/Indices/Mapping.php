<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use Closure;

/**
 * @see https://docs.opensearch.org/latest/field-types/
 * @see https://docs.opensearch.org/latest/mappings/
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
     * Get the mapping properties builder.
     */
    public function properties(): MappingProperties
    {
        return $this->properties;
    }

    /**
     * Add a field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function field(string $name, string $type, array $parameters = []): self
    {
        $this->properties->field($name, $type, $parameters);

        return $this;
    }

    /**
     * Add an alias field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/alias/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function alias(string $name, array $parameters = []): self
    {
        $this->properties->alias($name, $parameters);

        return $this;
    }

    /**
     * Add a binary field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/binary/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function binary(string $name, array $parameters = []): self
    {
        $this->properties->binary($name, $parameters);

        return $this;
    }

    /**
     * Add a boolean field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/boolean/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function boolean(string $name, array $parameters = []): self
    {
        $this->properties->boolean($name, $parameters);

        return $this;
    }

    /**
     * Add a byte field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function byte(string $name, array $parameters = []): self
    {
        $this->properties->byte($name, $parameters);

        return $this;
    }

    /**
     * Add a completion field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/autocomplete/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function completion(string $name, array $parameters = []): self
    {
        $this->properties->completion($name, $parameters);

        return $this;
    }

    /**
     * Add a constant keyword field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/string/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function constantKeyword(string $name, array $parameters = []): self
    {
        $this->properties->constantKeyword($name, $parameters);

        return $this;
    }

    /**
     * Add a date field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/date/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function date(string $name, array $parameters = []): self
    {
        $this->properties->date($name, $parameters);

        return $this;
    }

    /**
     * Add a date nanos field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/date/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function dateNanos(string $name, array $parameters = []): self
    {
        $this->properties->dateNanos($name, $parameters);

        return $this;
    }

    /**
     * Add a date range field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/range/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function dateRange(string $name, array $parameters = []): self
    {
        $this->properties->dateRange($name, $parameters);

        return $this;
    }

    /**
     * Add a dense vector field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/knn-vector/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function denseVector(string $name, array $parameters = []): self
    {
        $this->properties->denseVector($name, $parameters);

        return $this;
    }

    /**
     * Add a double field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function double(string $name, array $parameters = []): self
    {
        $this->properties->double($name, $parameters);

        return $this;
    }

    /**
     * Add a double range field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/range/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function doubleRange(string $name, array $parameters = []): self
    {
        $this->properties->doubleRange($name, $parameters);

        return $this;
    }

    /**
     * Add a flattened field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/flat-object/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function flattened(string $name, array $parameters = []): self
    {
        $this->properties->flattened($name, $parameters);

        return $this;
    }

    /**
     * Add a float field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function float(string $name, array $parameters = []): self
    {
        $this->properties->float($name, $parameters);

        return $this;
    }

    /**
     * Add a float range field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/range/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function floatRange(string $name, array $parameters = []): self
    {
        $this->properties->floatRange($name, $parameters);

        return $this;
    }

    /**
     * Add a geo point field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/geopoint/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function geoPoint(string $name, array $parameters = []): self
    {
        $this->properties->geoPoint($name, $parameters);

        return $this;
    }

    /**
     * Add a geo shape field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/geoshape/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function geoShape(string $name, array $parameters = []): self
    {
        $this->properties->geoShape($name, $parameters);

        return $this;
    }

    /**
     * Add a half float field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function halfFloat(string $name, array $parameters = []): self
    {
        $this->properties->halfFloat($name, $parameters);

        return $this;
    }

    /**
     * Add a histogram field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function histogram(string $name, array $parameters = []): self
    {
        $this->properties->histogram($name, $parameters);

        return $this;
    }

    /**
     * Add an integer field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function integer(string $name, array $parameters = []): self
    {
        $this->properties->integer($name, $parameters);

        return $this;
    }

    /**
     * Add an integer range field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/range/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function integerRange(string $name, array $parameters = []): self
    {
        $this->properties->integerRange($name, $parameters);

        return $this;
    }

    /**
     * Add an IP field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/ip/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function ip(string $name, array $parameters = []): self
    {
        $this->properties->ip($name, $parameters);

        return $this;
    }

    /**
     * Add an IP range field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/range/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function ipRange(string $name, array $parameters = []): self
    {
        $this->properties->ipRange($name, $parameters);

        return $this;
    }

    /**
     * Add a join field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/join/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function join(string $name, array $parameters = []): self
    {
        $this->properties->join($name, $parameters);

        return $this;
    }

    /**
     * Add a keyword field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/string/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function keyword(string $name, array $parameters = []): self
    {
        $this->properties->keyword($name, $parameters);

        return $this;
    }

    /**
     * Add a long field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function long(string $name, array $parameters = []): self
    {
        $this->properties->long($name, $parameters);

        return $this;
    }

    /**
     * Add a long range field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/range/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function longRange(string $name, array $parameters = []): self
    {
        $this->properties->longRange($name, $parameters);

        return $this;
    }

    /**
     * Add an object field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/object/
     *
     * @param  Closure|array<string, mixed>  $parameters
     */
    public function object(string $name, Closure|array $parameters = []): self
    {
        $this->properties->object($name, $parameters);

        return $this;
    }

    /**
     * Add a nested field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/nested/
     *
     * @param  Closure|array<string, mixed>  $parameters
     */
    public function nested(string $name, Closure|array $parameters = []): self
    {
        $this->properties->nested($name, $parameters);

        return $this;
    }

    /**
     * Add a percolator field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/percolator/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function percolator(string $name, array $parameters = []): self
    {
        $this->properties->percolator($name, $parameters);

        return $this;
    }

    /**
     * Add a rank feature field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/rank/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function rankFeature(string $name, array $parameters = []): self
    {
        $this->properties->rankFeature($name, $parameters);

        return $this;
    }

    /**
     * Add a rank features field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/rank/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function rankFeatures(string $name, array $parameters = []): self
    {
        $this->properties->rankFeatures($name, $parameters);

        return $this;
    }

    /**
     * Add a scaled float field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function scaledFloat(string $name, array $parameters = []): self
    {
        $this->properties->scaledFloat($name, $parameters);

        return $this;
    }

    /**
     * Add a search-as-you-type field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/autocomplete/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function searchAsYouType(string $name, array $parameters = []): self
    {
        $this->properties->searchAsYouType($name, $parameters);

        return $this;
    }

    /**
     * Add a shape field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/xy-shape/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function shape(string $name, array $parameters = []): self
    {
        $this->properties->shape($name, $parameters);

        return $this;
    }

    /**
     * Add a short field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/numeric/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function short(string $name, array $parameters = []): self
    {
        $this->properties->short($name, $parameters);

        return $this;
    }

    /**
     * Add a sparse vector field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/sparse-vector/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function sparseVector(string $name, array $parameters = []): self
    {
        $this->properties->sparseVector($name, $parameters);

        return $this;
    }

    /**
     * Add a text field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/string/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function text(string $name, array $parameters = []): self
    {
        $this->properties->text($name, $parameters);

        return $this;
    }

    /**
     * Add a token count field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/string/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function tokenCount(string $name, array $parameters = []): self
    {
        $this->properties->tokenCount($name, $parameters);

        return $this;
    }

    /**
     * Add a wildcard field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/string/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function wildcard(string $name, array $parameters = []): self
    {
        $this->properties->wildcard($name, $parameters);

        return $this;
    }

    /**
     * Enable OpenSearch field name indexing.
     *
     * @see https://docs.opensearch.org/latest/mappings/metadata-fields/field-names/
     */
    public function enableFieldNames(): self
    {
        $this->isFieldNamesEnabled = true;

        return $this;
    }

    /**
     * Disable OpenSearch field name indexing.
     *
     * @see https://docs.opensearch.org/latest/mappings/metadata-fields/field-names/
     */
    public function disableFieldNames(): self
    {
        $this->isFieldNamesEnabled = false;

        return $this;
    }

    /**
     * Enable OpenSearch source storage.
     *
     * @see https://docs.opensearch.org/latest/mappings/metadata-fields/source/
     */
    public function enableSource(): self
    {
        $this->isSourceEnabled = true;

        return $this;
    }

    /**
     * Disable OpenSearch source storage.
     *
     * @see https://docs.opensearch.org/latest/mappings/metadata-fields/source/
     */
    public function disableSource(): self
    {
        $this->isSourceEnabled = false;

        return $this;
    }

    /**
     * Add a dynamic template definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/#dynamic-templates
     *
     * @param  array<string, mixed>  $parameters
     */
    public function dynamicTemplate(string $name, array $parameters): self
    {
        $this->dynamicTemplates[] = [$name => $parameters];

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
