<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use stdClass;

test('array casting with query', function () {
    $request = new SearchRequest([
        'term' => [
            'user' => 'foo',
        ],
    ]);

    $this->assertSame([
        'body' => [
            'query' => [
                'term' => [
                    'user' => 'foo',
                ],
            ],
        ],
    ], $request->toArray());
});

test('array casting with query and highlight', function () {
    $request = new SearchRequest([
        'match' => [
            'content' => 'foo',
        ],
    ]);

    $request->highlight([
        'fields' => [
            'content' => new stdClass,
        ],
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match' => [
                    'content' => 'foo',
                ],
            ],
            'highlight' => [
                'fields' => [
                    'content' => new stdClass,
                ],
            ],
        ],
    ], $request->toArray());
});

test('array casting with query and sort', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->sort([
        ['title' => 'asc'],
        '_score',
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'sort' => [
                ['title' => 'asc'],
                '_score',
            ],
        ],
    ], $request->toArray());
});

test('array casting with query and search after', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->searchAfter([
        '2026-07-07T12:00:00.000Z',
        123,
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'search_after' => [
                '2026-07-07T12:00:00.000Z',
                123,
            ],
        ],
    ], $request->toArray());
});

test('array casting with query and rescore', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->rescore([
        'window_size' => 50,
        'query' => [
            'rescore_query' => [
                'match_phrase' => [
                    'message' => [
                        'query' => 'the quick brown',
                        'slop' => 2,
                    ],
                ],
            ],
            'query_weight' => 0.7,
            'rescore_query_weight' => 1.2,
        ],
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'rescore' => [
                'window_size' => 50,
                'query' => [
                    'rescore_query' => [
                        'match_phrase' => [
                            'message' => [
                                'query' => 'the quick brown',
                                'slop' => 2,
                            ],
                        ],
                    ],
                    'query_weight' => 0.7,
                    'rescore_query_weight' => 1.2,
                ],
            ],
        ],
    ], $request->toArray());
});

test('array casting with query and from', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->from(10);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'from' => 10,
        ],
    ], $request->toArray());
});

test('array casting with query and size', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->size(100);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'size' => 100,
        ],
    ], $request->toArray());
});

test('array casting with query and suggest', function () {
    $request = new SearchRequest([
        'match_none' => new stdClass,
    ]);

    $request->suggest([
        'color_suggestion' => [
            'text' => 'red',
            'term' => [
                'field' => 'color',
            ],
        ],
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_none' => new stdClass,
            ],
            'suggest' => [
                'color_suggestion' => [
                    'text' => 'red',
                    'term' => [
                        'field' => 'color',
                    ],
                ],
            ],
        ],
    ], $request->toArray());
});

dataset('source filters', [
    [false],
    ['obj1.*'],
    [['obj1.*', 'obj2.*']],
    [['includes' => ['obj1.*', 'obj2.*'], 'excludes' => ['*.description']]],
]);

test('array casting with source', function ($source) {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->source($source);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            '_source' => $source,
        ],
    ], $request->toArray());
})->with('source filters');

test('array casting with collapse', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->collapse([
        'field' => 'user',
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'collapse' => [
                'field' => 'user',
            ],
        ],
    ], $request->toArray());
});

test('array casting with aggregations', function () {
    $request = new SearchRequest;

    $request->aggregations([
        'min_price' => [
            'min' => [
                'field' => 'price',
            ],
        ],
    ]);

    $this->assertEquals([
        'body' => [
            'aggregations' => [
                'min_price' => [
                    'min' => [
                        'field' => 'price',
                    ],
                ],
            ],
        ],
    ], $request->toArray());
});

test('array casting with post filter', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->postFilter([
        'term' => [
            'color' => 'red',
        ],
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'post_filter' => [
                'term' => [
                    'color' => 'red',
                ],
            ],
        ],
    ], $request->toArray());
});

test('array casting with track total hits', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->trackTotalHits(100);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'track_total_hits' => 100,
        ],
    ], $request->toArray());
});

test('array casting with indices boost', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->indicesBoost([
        ['my-alias' => 1.4],
        ['my-index' => 1.3],
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'indices_boost' => [
                ['my-alias' => 1.4],
                ['my-index' => 1.3],
            ],
        ],
    ], $request->toArray());
});

test('array casting with track scores', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->trackScores(true);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'track_scores' => true,
        ],
    ], $request->toArray());
});

test('array casting with script fields', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->scriptFields([
        'my_doubled_field' => [
            'script' => [
                'lang' => 'painless',
                'source' => 'doc[params.field] * params.multiplier',
                'params' => [
                    'field' => 'my_field',
                    'multiplier' => 2,
                ],
            ],
        ],
    ]);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'script_fields' => [
                'my_doubled_field' => [
                    'script' => [
                        'lang' => 'painless',
                        'source' => 'doc[params.field] * params.multiplier',
                        'params' => [
                            'field' => 'my_field',
                            'multiplier' => 2,
                        ],
                    ],
                ],
            ],
        ],
    ], $request->toArray());
});

test('array casting with min score', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->minScore(0.5);

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'min_score' => 0.5,
        ],
    ], $request->toArray());
});

test('array casting with search type', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->searchType('query_then_fetch');

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
        ],
        'search_type' => 'query_then_fetch',
    ], $request->toArray());
});

test('array casting with preference', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->preference('_local');

    $this->assertEquals([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
        ],
        'preference' => '_local',
    ], $request->toArray());
});
