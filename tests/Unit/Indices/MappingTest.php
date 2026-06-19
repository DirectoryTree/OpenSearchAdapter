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
