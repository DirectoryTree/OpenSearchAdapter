<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Alias;

test('alias getters', function () {
    $alias = new Alias('2030', ['term' => ['year' => 2030]], 'year');

    $this->assertSame('2030', $alias->name());
    $this->assertSame(['term' => ['year' => 2030]], $alias->filter());
    $this->assertSame('year', $alias->routing());
});

test('array casting without filter and routing', function () {
    $alias = new Alias('2030');

    $this->assertSame([], $alias->toArray());
});

test('array casting with filter and routing', function () {
    $alias = new Alias('2030', ['term' => ['year' => 2030]], 'year');

    $this->assertSame([
        'routing' => 'year',
        'filter' => [
            'term' => [
                'year' => 2030,
            ],
        ],
    ], $alias->toArray());
});

test('write index can be configured', function () {
    $alias = new Alias('2030', isWriteIndex: true);

    expect($alias->isWriteIndex())->toBeTrue()
        ->and($alias->toArray())->toBe([
            'is_write_index' => true,
        ]);
});

test('write index false is included in the payload', function () {
    $alias = new Alias('2030', isWriteIndex: false);

    expect($alias->isWriteIndex())->toBeFalse()
        ->and($alias->toArray())->toBe([
            'is_write_index' => false,
        ]);
});
