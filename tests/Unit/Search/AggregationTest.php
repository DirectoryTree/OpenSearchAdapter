<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Aggregation;
use DirectoryTree\OpenSearchAdapter\Search\Bucket;

beforeEach(function () {
    $this->aggregation = new Aggregation([
        'doc_count_error_upper_bound' => 0,
        'sum_other_doc_count' => 0,
        'buckets' => [
            [
                'key' => 'electronic',
                'doc_count' => 6,
            ],
        ],
    ]);
});

it('retrieves buckets', function () {
    $this->assertEquals([
        new Bucket([
            'key' => 'electronic',
            'doc_count' => 6,
        ]),
    ], $this->aggregation->buckets());
});

it('retrieves raw representation', function () {
    $this->assertSame([
        'doc_count_error_upper_bound' => 0,
        'sum_other_doc_count' => 0,
        'buckets' => [
            [
                'key' => 'electronic',
                'doc_count' => 6,
            ],
        ],
    ], $this->aggregation->raw());
});
