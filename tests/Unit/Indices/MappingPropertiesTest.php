<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\MappingProperties;

dataset('mapping property setters', [
    [
        'type' => 'geoPoint',
        'name' => 'location',
        'parameters' => [
            'null_value' => null,
        ],
        'expected' => [
            'location' => [
                'type' => 'geo_point',
                'null_value' => null,
            ],
        ],
    ],
    [
        'type' => 'text',
        'name' => 'description',
        'parameters' => [
            'boost' => 1,
        ],
        'expected' => [
            'description' => [
                'type' => 'text',
                'boost' => 1,
            ],
        ],
    ],
    [
        'type' => 'keyword',
        'name' => 'age',
        'parameters' => [],
        'expected' => [
            'age' => [
                'type' => 'keyword',
            ],
        ],
    ],
    [
        'type' => 'flatObject',
        'name' => 'metadata',
        'parameters' => [],
        'expected' => [
            'metadata' => [
                'type' => 'flat_object',
            ],
        ],
    ],
    [
        'type' => 'flattened',
        'name' => 'metadata',
        'parameters' => [],
        'expected' => [
            'metadata' => [
                'type' => 'flat_object',
            ],
        ],
    ],
    [
        'type' => 'knnVector',
        'name' => 'embedding',
        'parameters' => [
            'dimension' => 1536,
        ],
        'expected' => [
            'embedding' => [
                'type' => 'knn_vector',
                'dimension' => 1536,
            ],
        ],
    ],
    [
        'type' => 'denseVector',
        'name' => 'embedding',
        'parameters' => [
            'dimension' => 1536,
        ],
        'expected' => [
            'embedding' => [
                'type' => 'knn_vector',
                'dimension' => 1536,
            ],
        ],
    ],
    [
        'type' => 'xyShape',
        'name' => 'bounds',
        'parameters' => [],
        'expected' => [
            'bounds' => [
                'type' => 'xy_shape',
            ],
        ],
    ],
    [
        'type' => 'shape',
        'name' => 'bounds',
        'parameters' => [],
        'expected' => [
            'bounds' => [
                'type' => 'xy_shape',
            ],
        ],
    ],
    [
        'type' => 'object',
        'name' => 'user',
        'parameters' => [
            'properties' => [
                'age' => [
                    'type' => 'keyword',
                ],
            ],
        ],
        'expected' => [
            'user' => [
                'type' => 'object',
                'properties' => [
                    'age' => [
                        'type' => 'keyword',
                    ],
                ],
            ],
        ],
    ],
    [
        'type' => 'object',
        'name' => 'user',
        'parameters' => [
            'properties' => (new MappingProperties)->keyword('age'),
        ],
        'expected' => [
            'user' => [
                'type' => 'object',
                'properties' => [
                    'age' => [
                        'type' => 'keyword',
                    ],
                ],
            ],
        ],
    ],
    [
        'type' => 'object',
        'name' => 'user',
        'parameters' => [],
        'expected' => [
            'user' => [
                'type' => 'object',
            ],
        ],
    ],
]);

test('property setter', function (string $type, string $name, $parameters, array $expected) {
    $actual = (new MappingProperties)->$type($name, $parameters);
    $this->assertEquals($expected, $actual->toArray());
})->with('mapping property setters');

test('field can be set using a custom type', function () {
    $actual = (new MappingProperties)->field('embedding', 'custom_vector', [
        'dimension' => 1536,
    ]);

    $this->assertSame([
        'embedding' => [
            'type' => 'custom_vector',
            'dimension' => 1536,
        ],
    ], $actual->toArray());
});

test('object properties may be configured with a closure', function () {
    $actual = (new MappingProperties)->object('user', function (MappingProperties $properties) {
        $properties->integer('age');

        return [
            'properties' => $properties,
            'dynamic' => true,
        ];
    });

    $this->assertEquals([
        'user' => [
            'type' => 'object',
            'properties' => [
                'age' => [
                    'type' => 'integer',
                ],
            ],
            'dynamic' => true,
        ],
    ], $actual->toArray());
});

test('nested properties may be configured with a closure', function () {
    $actual = (new MappingProperties)->nested('user', function (MappingProperties $properties) {
        $properties->keyword('age');

        return [
            'properties' => $properties,
            'dynamic' => true,
        ];
    });

    $this->assertEquals([
        'user' => [
            'type' => 'nested',
            'properties' => [
                'age' => [
                    'type' => 'keyword',
                ],
            ],
            'dynamic' => true,
        ],
    ], $actual->toArray());
});
