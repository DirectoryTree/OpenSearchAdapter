<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\TotalHits;

test('total hit values can be retrieved', function () {
    $total = new TotalHits([
        'value' => 100,
        'relation' => 'eq',
    ]);

    $this->assertSame(100, $total->value());
    $this->assertSame('eq', $total->relation());
    $this->assertTrue($total->isExact());
});

test('integer total hit values can be retrieved', function () {
    $total = new TotalHits(100);

    $this->assertSame(100, $total->value());
    $this->assertSame('eq', $total->relation());
    $this->assertTrue($total->isExact());
});

test('raw representation can be retrieved', function () {
    $total = new TotalHits([
        'value' => 10000,
        'relation' => 'gte',
    ]);

    $this->assertSame([
        'value' => 10000,
        'relation' => 'gte',
    ], $total->raw());
});
