<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Alias;

it('retrieves alias values', function () {
    $alias = new Alias('2030', ['term' => ['year' => 2030]], 'year');

    $this->assertSame('2030', $alias->name());
    $this->assertSame(['term' => ['year' => 2030]], $alias->filter());
    $this->assertSame('year', $alias->routing());
});

it('casts to an array without a filter or routing', function () {
    $alias = new Alias('2030');

    $this->assertSame([], $alias->toArray());
});

it('casts to an array with a filter and routing', function () {
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

it('configures write index', function () {
    $alias = new Alias('2030', isWriteIndex: true);

    expect($alias->isWriteIndex())->toBeTrue()
        ->and($alias->toArray())->toBe([
            'is_write_index' => true,
        ]);
});

it('includes a false write index in the payload', function () {
    $alias = new Alias('2030', isWriteIndex: false);

    expect($alias->isWriteIndex())->toBeFalse()
        ->and($alias->toArray())->toBe([
            'is_write_index' => false,
        ]);
});
