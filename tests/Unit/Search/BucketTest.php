<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Bucket;

beforeEach(function () {
    $this->bucket = new Bucket([
        'key' => 'electronic',
        'doc_count' => 6,
    ]);
});

test('key can be retrieved', function () {
    $this->assertSame('electronic', $this->bucket->key());
});

test('doc count can be retrieved', function () {
    $this->assertSame(6, $this->bucket->docCount());
});

test('raw representation can be retrieved', function () {
    $this->assertSame([
        'key' => 'electronic',
        'doc_count' => 6,
    ], $this->bucket->raw());
});
