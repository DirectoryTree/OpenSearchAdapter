<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Mapping;

test('field names can be disabled', function () {
    $mapping = (new Mapping)->disableFieldNames();

    $this->assertSame([
        '_field_names' => [
            'enabled' => false,
        ],
    ], $mapping->toArray());
});

test('field names can be enabled', function () {
    $mapping = (new Mapping)->enableFieldNames();

    $this->assertSame([
        '_field_names' => [
            'enabled' => true,
        ],
    ], $mapping->toArray());
});

test('source can be disabled', function () {
    $mapping = (new Mapping)->disableSource();

    $this->assertSame([
        '_source' => [
            'enabled' => false,
        ],
    ], $mapping->toArray());
});

test('source can be enabled', function () {
    $mapping = (new Mapping)->enableSource();

    $this->assertSame([
        '_source' => [
            'enabled' => true,
        ],
    ], $mapping->toArray());
});

test('default array casting', function () {
    $this->assertSame([], (new Mapping)->toArray());
});

test('configured array casting', function () {
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

test('field can be set using a custom type', function () {
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

test('properties builder can be retrieved', function () {
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
