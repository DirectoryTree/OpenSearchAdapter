<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Alias;

test('alias getters', function () {
    $alias = new Alias('2030', ['term' => ['year' => 2030]], 'year');

    $this->assertSame('2030', $alias->name());
    $this->assertSame(['term' => ['year' => 2030]], $alias->filter());
    $this->assertSame('year', $alias->routing());
});
