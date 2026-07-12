<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Bucket;

beforeEach(function () {
    $this->bucket = new Bucket([
        'key' => 'electronic',
        'doc_count' => 6,
    ]);
});

it('retrieves key', function () {
    $this->assertSame('electronic', $this->bucket->key());
});

it('retrieves doc count', function () {
    $this->assertSame(6, $this->bucket->docCount());
});

it('retrieves raw representation', function () {
    $this->assertSame([
        'key' => 'electronic',
        'doc_count' => 6,
    ], $this->bucket->raw());
});
