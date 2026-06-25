<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

use Closure;

/**
 * @see https://docs.opensearch.org/latest/field-types/
 * @see https://docs.opensearch.org/latest/mappings/
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
     * Add a field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function field(string $name, string $type, array $parameters = []): self
    {
        $this->properties[$name] = ['type' => $type] + $parameters;

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
        return $this->field($name, 'alias', $parameters);
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
        return $this->field($name, 'binary', $parameters);
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
        return $this->field($name, 'boolean', $parameters);
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
        return $this->field($name, 'byte', $parameters);
    }

    /**
     * Add a completion field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/completion/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function completion(string $name, array $parameters = []): self
    {
        return $this->field($name, 'completion', $parameters);
    }

    /**
     * Add a constant keyword field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/keyword/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function constantKeyword(string $name, array $parameters = []): self
    {
        return $this->field($name, 'constant_keyword', $parameters);
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
        return $this->field($name, 'date', $parameters);
    }

    /**
     * Add a date nanos field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/date-nanos/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function dateNanos(string $name, array $parameters = []): self
    {
        return $this->field($name, 'date_nanos', $parameters);
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
        return $this->field($name, 'date_range', $parameters);
    }

    /**
     * Add a k-NN vector field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/knn-vector/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function knnVector(string $name, array $parameters = []): self
    {
        return $this->field($name, 'knn_vector', $parameters);
    }

    /**
     * Add a k-NN vector field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/knn-vector/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function denseVector(string $name, array $parameters = []): self
    {
        return $this->knnVector($name, $parameters);
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
        return $this->field($name, 'double', $parameters);
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
        return $this->field($name, 'double_range', $parameters);
    }

    /**
     * Add a flat object field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/flat-object/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function flatObject(string $name, array $parameters = []): self
    {
        return $this->field($name, 'flat_object', $parameters);
    }

    /**
     * Add a flat object field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/flat-object/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function flattened(string $name, array $parameters = []): self
    {
        return $this->flatObject($name, $parameters);
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
        return $this->field($name, 'float', $parameters);
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
        return $this->field($name, 'float_range', $parameters);
    }

    /**
     * Add a geo point field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/geo-point/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function geoPoint(string $name, array $parameters = []): self
    {
        return $this->field($name, 'geo_point', $parameters);
    }

    /**
     * Add a geo shape field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/geo-shape/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function geoShape(string $name, array $parameters = []): self
    {
        return $this->field($name, 'geo_shape', $parameters);
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
        return $this->field($name, 'half_float', $parameters);
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
        return $this->field($name, 'histogram', $parameters);
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
        return $this->field($name, 'integer', $parameters);
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
        return $this->field($name, 'integer_range', $parameters);
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
        return $this->field($name, 'ip', $parameters);
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
        return $this->field($name, 'ip_range', $parameters);
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
        return $this->field($name, 'join', $parameters);
    }

    /**
     * Add a keyword field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/keyword/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function keyword(string $name, array $parameters = []): self
    {
        return $this->field($name, 'keyword', $parameters);
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
        return $this->field($name, 'long', $parameters);
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
        return $this->field($name, 'long_range', $parameters);
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
        $this->properties[$name] = ['type' => 'object'];

        if (! empty($parameters)) {
            $this->properties[$name] += $this->normalizeNestedParameters($parameters);
        }

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
        $this->properties[$name] = ['type' => 'nested'];

        if (! empty($parameters)) {
            $this->properties[$name] += $this->normalizeNestedParameters($parameters);
        }

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
        return $this->field($name, 'percolator', $parameters);
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
        return $this->field($name, 'rank_feature', $parameters);
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
        return $this->field($name, 'rank_features', $parameters);
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
        return $this->field($name, 'scaled_float', $parameters);
    }

    /**
     * Add a search-as-you-type field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/search-as-you-type/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function searchAsYouType(string $name, array $parameters = []): self
    {
        return $this->field($name, 'search_as_you_type', $parameters);
    }

    /**
     * Add an xy shape field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/xy-shape/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function xyShape(string $name, array $parameters = []): self
    {
        return $this->field($name, 'xy_shape', $parameters);
    }

    /**
     * Add an xy shape field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/xy-shape/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function shape(string $name, array $parameters = []): self
    {
        return $this->xyShape($name, $parameters);
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
        return $this->field($name, 'short', $parameters);
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
        return $this->field($name, 'sparse_vector', $parameters);
    }

    /**
     * Add a text field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/text/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function text(string $name, array $parameters = []): self
    {
        return $this->field($name, 'text', $parameters);
    }

    /**
     * Add a token count field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/token-count/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function tokenCount(string $name, array $parameters = []): self
    {
        return $this->field($name, 'token_count', $parameters);
    }

    /**
     * Add a wildcard field definition.
     *
     * @see https://docs.opensearch.org/latest/mappings/supported-field-types/wildcard/
     *
     * @param  array<string, mixed>  $parameters
     */
    public function wildcard(string $name, array $parameters = []): self
    {
        return $this->field($name, 'wildcard', $parameters);
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
    protected function normalizeNestedParameters(Closure|array $parameters): array
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
