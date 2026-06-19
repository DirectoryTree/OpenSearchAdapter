<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Aggregation;
use DirectoryTree\OpenSearchAdapter\Search\Hit;
use DirectoryTree\OpenSearchAdapter\Search\SearchResponse;
use DirectoryTree\OpenSearchAdapter\Search\Suggestion;

test('hits can be retrieved', function () {
    $searchResponse = new SearchResponse([
        'hits' => [
            'hits' => [
                [
                    '_id' => '1',
                    '_source' => ['title' => 'foo'],
                ],
            ],
        ],
    ]);

    $this->assertEquals(
        [new Hit(['_id' => '1', '_source' => ['title' => 'foo']])],
        $searchResponse->hits()
    );
});

test('total number of hits can be retrieved', function () {
    $searchResponse = new SearchResponse([
        'hits' => [
            'total' => ['value' => 100],
        ],
    ]);

    $this->assertSame(100, $searchResponse->total());
});

test('empty array is returned when suggestions are not present', function () {
    $searchResponse = new SearchResponse([
        'hits' => [],
    ]);

    $this->assertSame([], $searchResponse->suggestions());
});

test('suggestions can be retrieved', function () {
    $searchResponse = new SearchResponse([
        'hits' => [],
        'suggest' => [
            'color_suggestion' => [
                [
                    'text' => 'red',
                    'offset' => 0,
                    'length' => 3,
                    'options' => [],
                ],
                [
                    'text' => 'blue',
                    'offset' => 4,
                    'length' => 4,
                    'options' => [],
                ],
            ],
        ],
    ]);

    $this->assertEquals([
        'color_suggestion' => [
            new Suggestion([
                'text' => 'red',
                'offset' => 0,
                'length' => 3,
                'options' => [],
            ]),
            new Suggestion([
                'text' => 'blue',
                'offset' => 4,
                'length' => 4,
                'options' => [],
            ]),
        ],
    ], $searchResponse->suggestions());
});

test('aggregations can be retrieved', function () {
    $searchResponse = new SearchResponse([
        'hits' => [],
        'aggregations' => [
            'min_price' => [
                'value' => 10,
            ],
        ],
    ]);

    $this->assertEquals([
        'min_price' => new Aggregation([
            'value' => 10,
        ]),
    ], $searchResponse->aggregations());
});

test('raw representation can be retrieved', function () {
    $searchResponse = new SearchResponse([
        'hits' => [
            'total' => ['value' => 100],
            'hits' => [
                [
                    '_id' => '1',
                    '_source' => ['title' => 'foo'],
                ],
                [
                    '_id' => '2',
                    '_source' => ['title' => 'bar'],
                ],
            ],
        ],
    ]);

    $this->assertSame([
        'hits' => [
            'total' => ['value' => 100],
            'hits' => [
                [
                    '_id' => '1',
                    '_source' => ['title' => 'foo'],
                ],
                [
                    '_id' => '2',
                    '_source' => ['title' => 'bar'],
                ],
            ],
        ],
    ], $searchResponse->raw());
});
