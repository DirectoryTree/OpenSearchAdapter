<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\ShardStatistics;

test('shard statistics can be retrieved', function () {
    $shards = new ShardStatistics([
        'total' => 3,
        'successful' => 2,
        'skipped' => 1,
        'failed' => 1,
        'failures' => [
            ['reason' => ['type' => 'query_shard_exception']],
        ],
    ]);

    $this->assertSame(3, $shards->total());
    $this->assertSame(2, $shards->successful());
    $this->assertSame(1, $shards->skipped());
    $this->assertSame(1, $shards->failed());
    $this->assertSame([
        ['reason' => ['type' => 'query_shard_exception']],
    ], $shards->failures());
});

test('raw representation can be retrieved', function () {
    $shards = new ShardStatistics([
        'total' => 1,
        'successful' => 1,
        'failed' => 0,
    ]);

    $this->assertSame([
        'total' => 1,
        'successful' => 1,
        'failed' => 0,
    ], $shards->raw());
});
