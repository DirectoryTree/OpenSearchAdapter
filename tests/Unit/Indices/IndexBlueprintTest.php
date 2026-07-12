<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;

it('uses null for mapping and settings by default', function () {
    $index = new IndexBlueprint('foo');

    $this->assertNull($index->settings());
    $this->assertNull($index->mapping());
});

it('retrieves index values', function () {
    $mapping = new Mapping;
    $settings = new Settings;
    $index = new IndexBlueprint('foo', $mapping, $settings);

    $this->assertSame('foo', $index->name());
    $this->assertSame($mapping, $index->mapping());
    $this->assertSame($settings, $index->settings());
});

it('casts to an array without mapping or settings', function () {
    $index = new IndexBlueprint('foo');

    $this->assertSame([
        'index' => 'foo',
    ], $index->toArray());
});

it('casts to an array with mapping and settings', function () {
    $index = new IndexBlueprint(
        'foo',
        (new Mapping)->text('title'),
        (new Settings)->index(['number_of_replicas' => 2])
    );

    $this->assertSame([
        'index' => 'foo',
        'body' => [
            'mappings' => [
                'properties' => [
                    'title' => [
                        'type' => 'text',
                    ],
                ],
            ],
            'settings' => [
                'index' => [
                    'number_of_replicas' => 2,
                ],
            ],
        ],
    ], $index->toArray());
});
