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

test('array casting with documented body options', function () {
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
        ->derived([
            'url' => [
                'type' => 'text',
                'script' => [
                    'source' => 'emit(doc["request"].value.splitOnToken(" ")[2])',
                ],
            ],
        ])
        ->storedFields(['title'])
        ->slice([
            'id' => 0,
            'max' => 10,
        ])
        ->pit('pit-id', '1m')
        ->explain()
        ->profile()
        ->temporarySearchPipeline([
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
        ->includeNamedQueriesScore()
        ->seqNoPrimaryTerm()
        ->stats(['group-1', 'group-2'])
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
            'derived' => [
                'url' => [
                    'type' => 'text',
                    'script' => [
                        'source' => 'emit(doc["request"].value.splitOnToken(" ")[2])',
                    ],
                ],
            ],
            'stored_fields' => ['title'],
            'slice' => [
                'id' => 0,
                'max' => 10,
            ],
            'pit' => [
                'id' => 'pit-id',
                'keep_alive' => '1m',
            ],
            'explain' => true,
            'profile' => true,
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
            'seq_no_primary_term' => true,
            'stats' => ['group-1', 'group-2'],
            'terminate_after' => 25,
            'timeout' => '250ms',
            'version' => true,
        ],
    ]);
});

test('array casting with documented search parameters', function () {
    $request = (new SearchRequest([
        'match_all' => new stdClass,
    ]))
        ->sourceIncludes(['title', 'author'])
        ->sourceExcludes('internal.*')
        ->allowNoIndices(false)
        ->allowPartialSearchResults(false)
        ->analyzer('standard')
        ->analyzeWildcard()
        ->batchedReduceSize(32)
        ->cancelAfterTimeInterval('10ms')
        ->ccsMinimizeRoundtrips(false)
        ->defaultOperator('AND')
        ->defaultField('title')
        ->expandWildcards('open,hidden')
        ->ignoreThrottled(true)
        ->ignoreUnavailable(true)
        ->lenient()
        ->maxConcurrentShardRequests(3)
        ->phaseTook()
        ->preFilterShardSize(16)
        ->queryString('title:hobbit')
        ->requestCache(true)
        ->restTotalHitsAsInt()
        ->routing('tenant-1')
        ->scroll('1m')
        ->searchPipeline('search-pipeline')
        ->suggestField('title')
        ->suggestMode('always')
        ->suggestSize(3)
        ->suggestText('hobit')
        ->typedKeys()
        ->verbosePipeline();

    expect($request->toArray())->toEqual([
        'body' => [
            'query' => [
                'match_all' => new stdClass,
            ],
        ],
        '_source_includes' => ['title', 'author'],
        '_source_excludes' => 'internal.*',
        'allow_no_indices' => false,
        'allow_partial_search_results' => false,
        'analyzer' => 'standard',
        'analyze_wildcard' => true,
        'batched_reduce_size' => 32,
        'cancel_after_time_interval' => '10ms',
        'ccs_minimize_roundtrips' => false,
        'default_operator' => 'AND',
        'df' => 'title',
        'expand_wildcards' => 'open,hidden',
        'ignore_throttled' => true,
        'ignore_unavailable' => true,
        'lenient' => true,
        'max_concurrent_shard_requests' => 3,
        'phase_took' => true,
        'pre_filter_shard_size' => 16,
        'q' => 'title:hobbit',
        'request_cache' => true,
        'rest_total_hits_as_int' => true,
        'routing' => 'tenant-1',
        'scroll' => '1m',
        'search_pipeline' => 'search-pipeline',
        'suggest_field' => 'title',
        'suggest_mode' => 'always',
        'suggest_size' => 3,
        'suggest_text' => 'hobit',
        'typed_keys' => true,
        'verbose_pipeline' => true,
    ]);
});

test('array casting with custom body options and parameters', function () {
    $request = (new SearchRequest)
        ->body('derived', [
            'full_name' => [
                'type' => 'keyword',
                'script' => [
                    'source' => "doc['first_name'].value + ' ' + doc['last_name'].value",
                ],
            ],
        ])
        ->parameter('filter_path', 'hits.hits._id');

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
        ],
        'filter_path' => 'hits.hits._id',
    ]);
});
