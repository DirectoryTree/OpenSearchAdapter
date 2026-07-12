<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use stdClass;

it('casts to an array with a query', function () {
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

it('casts to an array with a query and highlight', function () {
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

it('casts to an array with a query and sort', function () {
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

it('checks for sort clauses', function () {
    $request = new SearchRequest;

    expect($request->hasSort())->toBeFalse();

    $request->sort([]);

    expect($request->hasSort())->toBeFalse();

    $request->sort([
        ['title' => 'asc'],
    ]);

    expect($request->hasSort())->toBeTrue();
});

it('casts to an array with a query and search after', function () {
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

it('casts to an array with a point in time without keep alive', function () {
    $request = new SearchRequest([
        'match_all' => new stdClass,
    ]);

    $request->pit('pit-id');

    expect($request->toArray())->toEqual([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'pit' => [
                'id' => 'pit-id',
            ],
        ],
    ]);
});

it('casts to an array with a query and rescore', function () {
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

it('casts to an array with a query and from', function () {
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

it('casts to an array with a query and size', function () {
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

it('casts to an array with a query and suggest', function () {
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

it('casts to an array with source filtering', function ($source) {
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

it('casts to an array with collapse', function () {
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

it('casts to an array with aggregations', function () {
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

it('casts to an array with a post filter', function () {
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

it('casts to an array with track total hits', function () {
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

it('casts to an array with indices boost', function () {
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

it('casts to an array with track scores', function () {
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

it('casts to an array with script fields', function () {
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

it('casts to an array with a minimum score', function () {
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

it('casts to an array with a search type', function () {
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

it('casts to an array with a preference', function () {
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

it('casts to an array with documented body options', function () {
    $request = (new SearchRequest)
        ->query([
            'match_all' => new stdClass,
        ])
        ->aggs([
            'views' => [
                'avg' => [
                    'field' => 'views',
                ],
            ],
        ])
        ->fields([
            [
                'field' => 'published_at',
                'format' => 'strict_date_optional_time',
            ],
        ])
        ->docValueFields([
            [
                'field' => 'published_at',
                'format' => 'epoch_millis',
            ],
        ])
        ->storedFields(['title'])
        ->pit('pit-id', '1m')
        ->explain()
        ->profile()
        ->seqNoPrimaryTerm()
        ->terminateAfter(25)
        ->timeout('250ms')
        ->version();

    expect($request->toArray())->toEqual([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
            'aggs' => [
                'views' => [
                    'avg' => [
                        'field' => 'views',
                    ],
                ],
            ],
            'fields' => [
                [
                    'field' => 'published_at',
                    'format' => 'strict_date_optional_time',
                ],
            ],
            'docvalue_fields' => [
                [
                    'field' => 'published_at',
                    'format' => 'epoch_millis',
                ],
            ],
            'stored_fields' => ['title'],
            'pit' => [
                'id' => 'pit-id',
                'keep_alive' => '1m',
            ],
            'explain' => true,
            'profile' => true,
            'seq_no_primary_term' => true,
            'terminate_after' => 25,
            'timeout' => '250ms',
            'version' => true,
        ],
    ]);
});

it('casts to an array with documented search parameters', function () {
    $request = (new SearchRequest([
        'match_all' => new stdClass,
    ]))
        ->sourceIncludes(['title', 'author'])
        ->sourceExcludes('internal.*')
        ->requestCache(true)
        ->routing('tenant-1')
        ->scroll('1m')
        ->searchPipeline('search-pipeline');

    expect($request->toArray())->toEqual([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
        ],
        '_source_includes' => ['title', 'author'],
        '_source_excludes' => 'internal.*',
        'request_cache' => true,
        'routing' => 'tenant-1',
        'scroll' => '1m',
        'search_pipeline' => 'search-pipeline',
    ]);
});

it('casts to an array with custom body options and parameters', function () {
    $request = (new SearchRequest)
        ->body('derived', [
            'full_name' => [
                'type' => 'keyword',
                'script' => [
                    'source' => "doc['first_name'].value + ' ' + doc['last_name'].value",
                ],
            ],
        ])
        ->body('slice', [
            'id' => 0,
            'max' => 10,
        ])
        ->body('search_pipeline', [
            'request_processors' => [
                [
                    'filter_query' => [
                        'query' => [
                            'term' => [
                                'visibility' => 'public',
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->body('include_named_queries_score', true)
        ->body('stats', ['group-1', 'group-2'])
        ->parameter('allow_no_indices', false)
        ->parameter('analyze_wildcard', true)
        ->parameter('default_operator', 'AND')
        ->parameter('df', 'title')
        ->parameter('filter_path', 'hits.hits._id')
        ->parameter('q', 'title:hobbit')
        ->parameter('typed_keys', true);

    expect($request->toArray())->toEqual([
        'body' => [
            'derived' => [
                'full_name' => [
                    'type' => 'keyword',
                    'script' => [
                        'source' => "doc['first_name'].value + ' ' + doc['last_name'].value",
                    ],
                ],
            ],
            'slice' => [
                'id' => 0,
                'max' => 10,
            ],
            'search_pipeline' => [
                'request_processors' => [
                    [
                        'filter_query' => [
                            'query' => [
                                'term' => [
                                    'visibility' => 'public',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'include_named_queries_score' => true,
            'stats' => ['group-1', 'group-2'],
        ],
        'allow_no_indices' => false,
        'analyze_wildcard' => true,
        'default_operator' => 'AND',
        'df' => 'title',
        'filter_path' => 'hits.hits._id',
        'q' => 'title:hobbit',
        'typed_keys' => true,
    ]);
});
