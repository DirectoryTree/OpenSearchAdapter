<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Search\Aggregation;
use DirectoryTree\OpenSearchAdapter\Search\Hit;
use DirectoryTree\OpenSearchAdapter\Search\SearchResponse;
use DirectoryTree\OpenSearchAdapter\Search\ShardStatistics;
use DirectoryTree\OpenSearchAdapter\Search\Suggestion;
use DirectoryTree\OpenSearchAdapter\Search\TotalHits;

it('creates an empty response', function () {
    $searchResponse = new SearchResponse;

    expect($searchResponse->hits())->toBe([])
        ->and($searchResponse->total())->toBeNull()
        ->and($searchResponse->raw())->toBe([]);
});

it('retrieves hits', function () {
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

it('retrieves total number of hits', function () {
    $searchResponse = new SearchResponse([
        'hits' => [
            'total' => ['value' => 100],
        ],
    ]);

    $this->assertSame(100, $searchResponse->total());
});

it('retrieves total hit metadata', function () {
    $searchResponse = new SearchResponse([
        'hits' => [
            'total' => [
                'value' => 100,
                'relation' => 'eq',
            ],
        ],
    ]);

    $this->assertEquals(
        new TotalHits(['value' => 100, 'relation' => 'eq']),
        $searchResponse->totalHits()
    );
});

it('retrieves integer total hit metadata', function () {
    $searchResponse = new SearchResponse([
        'hits' => [
            'total' => 100,
        ],
    ]);

    $this->assertEquals(
        new TotalHits(100),
        $searchResponse->totalHits()
    );
    $this->assertSame(100, $searchResponse->total());
});

it('retrieves execution metadata', function () {
    $searchResponse = new SearchResponse([
        'took' => 12,
        'timed_out' => true,
        '_shards' => [
            'total' => 3,
            'successful' => 3,
            'skipped' => 0,
            'failed' => 0,
        ],
        'hits' => [],
    ]);

    $this->assertSame(12, $searchResponse->took());
    $this->assertTrue($searchResponse->timedOut());
    $this->assertEquals(
        new ShardStatistics([
            'total' => 3,
            'successful' => 3,
            'skipped' => 0,
            'failed' => 0,
        ]),
        $searchResponse->shards()
    );
});

it('returns an empty array when suggestions are absent', function () {
    $searchResponse = new SearchResponse([
        'hits' => [],
    ]);

    $this->assertSame([], $searchResponse->suggestions());
});

it('retrieves suggestions', function () {
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

it('retrieves aggregations', function () {
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

it('retrieves raw representation', function () {
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

it('creates a fake response without hits', function () {
    $searchResponse = SearchResponse::fake();

    expect($searchResponse->hits())->toBe([])
        ->and($searchResponse->total())->toBe(0);
});

it('creates a fake response with documents', function () {
    $searchResponse = SearchResponse::fake([
        new Document('1', ['title' => 'First']),
        new Document('2', ['title' => 'Second']),
    ], 'posts');

    expect($searchResponse->total())->toBe(2)
        ->and($searchResponse->hits()[0]->index())->toBe('posts')
        ->and($searchResponse->hits()[0]->id())->toBe('1')
        ->and($searchResponse->hits()[0]->source())->toBe(['title' => 'First']);
});

it('creates a fake response with source arrays', function () {
    $searchResponse = SearchResponse::fake([
        ['title' => 'First'],
    ]);

    expect($searchResponse->hits()[0]->id())->toBe('1')
        ->and($searchResponse->hits()[0]->source())->toBe(['title' => 'First']);
});

it('creates a fake response with raw hits', function () {
    $searchResponse = SearchResponse::fake([
        [
            '_index' => 'articles',
            '_id' => 'post-1',
            '_score' => 4.2,
            '_source' => [
                'title' => 'First',
            ],
        ],
    ]);

    expect($searchResponse->hits()[0]->index())->toBe('articles')
        ->and($searchResponse->hits()[0]->id())->toBe('post-1')
        ->and($searchResponse->hits()[0]->score())->toBe(4.2);
});
