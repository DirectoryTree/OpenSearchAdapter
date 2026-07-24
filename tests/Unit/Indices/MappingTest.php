<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Mapping;

it('disables field names', function () {
    $mapping = (new Mapping)->disableFieldNames();

    $this->assertSame([
        '_field_names' => [
            'enabled' => false,
        ],
    ], $mapping->toArray());
});

it('enables field names', function () {
    $mapping = (new Mapping)->enableFieldNames();

    $this->assertSame([
        '_field_names' => [
            'enabled' => true,
        ],
    ], $mapping->toArray());
});

it('disables source', function () {
    $mapping = (new Mapping)->disableSource();

    $this->assertSame([
        '_source' => [
            'enabled' => false,
        ],
    ], $mapping->toArray());
});

it('enables source', function () {
    $mapping = (new Mapping)->enableSource();

    $this->assertSame([
        '_source' => [
            'enabled' => true,
        ],
    ], $mapping->toArray());
});

it('sets dynamic field mapping behavior', function (bool|string $dynamic) {
    $mapping = (new Mapping)->dynamic($dynamic);

    expect($mapping->toArray())->toBe([
        'dynamic' => $dynamic,
    ]);
})->with([
    true,
    false,
    'strict',
    'strict_allow_templates',
]);

it('casts default values to an empty array', function () {
    $this->assertSame([], (new Mapping)->toArray());
});

it('casts configured values to an array', function () {
    $mapping = (new Mapping)
        ->disableFieldNames()
        ->enableSource()
        ->text('foo')
        ->boolean('bar', [
            'boost' => 1,
        ])
        ->dynamicTemplate('integers', [
            'match_mapping_type' => 'long',
            'mapping' => [
                'type' => 'integer',
            ],
        ]);

    $this->assertSame([
        '_field_names' => [
            'enabled' => false,
        ],
        '_source' => [
            'enabled' => true,
        ],
        'properties' => [
            'foo' => [
                'type' => 'text',
            ],
            'bar' => [
                'type' => 'boolean',
                'boost' => 1,
            ],
        ],
        'dynamic_templates' => [
            [
                'integers' => [
                    'match_mapping_type' => 'long',
                    'mapping' => [
                        'type' => 'integer',
                    ],
                ],
            ],
        ],
    ], $mapping->toArray());
});

it('sets field using a custom type', function () {
    $mapping = (new Mapping)->field('embedding', 'custom_vector', [
        'dimension' => 1536,
    ]);

    $this->assertSame([
        'properties' => [
            'embedding' => [
                'type' => 'custom_vector',
                'dimension' => 1536,
            ],
        ],
    ], $mapping->toArray());
});

it('retrieves properties builder', function () {
    $mapping = new Mapping;

    $mapping->properties()->keyword('id');

    $this->assertSame([
        'properties' => [
            'id' => [
                'type' => 'keyword',
            ],
        ],
    ], $mapping->toArray());
});
