<?php

namespace DirectoryTree\OpenSearchAdapter\Indices;

/**
 * @see https://docs.opensearch.org/latest/api-reference/index-apis/create-index/
 */
class IndexBlueprint
{
    /**
     * Create a new index blueprint instance.
     *
     * @param  string  $name  The index name.
     */
    public function __construct(
        protected string $name,
        protected ?Mapping $mapping = null,
        protected ?Settings $settings = null,
    ) {}

    /**
     * Get the index name.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the index mapping definition.
     */
    public function mapping(): ?Mapping
    {
        return $this->mapping;
    }

    /**
     * Get the index settings definition.
     */
    public function settings(): ?Settings
    {
        return $this->settings;
    }

    /**
     * Get the OpenSearch create index payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [
            'index' => $this->name,
        ];

        if ($mapping = $this->mapping?->toArray()) {
            $params['body']['mappings'] = $mapping;
        }

        if ($settings = $this->settings?->toArray()) {
            $params['body']['settings'] = $settings;
        }

        return $params;
    }
}
