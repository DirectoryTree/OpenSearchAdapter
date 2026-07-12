<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\TotalHits;

it('retrieves total hit values', function () {
    $total = new TotalHits([
        'value' => 100,
        'relation' => 'eq',
    ]);

    $this->assertSame(100, $total->value());
    $this->assertSame('eq', $total->relation());
    $this->assertTrue($total->isExact());
});

it('retrieves integer total hit values', function () {
    $total = new TotalHits(100);

    $this->assertSame(100, $total->value());
    $this->assertSame('eq', $total->relation());
    $this->assertTrue($total->isExact());
});

it('retrieves raw representation', function () {
    $total = new TotalHits([
        'value' => 10000,
        'relation' => 'gte',
    ]);

    $this->assertSame([
        'value' => 10000,
        'relation' => 'gte',
    ], $total->raw());
});
