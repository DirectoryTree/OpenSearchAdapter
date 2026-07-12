<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Search\Highlight;
use DirectoryTree\OpenSearchAdapter\Search\Hit;

beforeEach(function () {
    $this->hit = new Hit([
        '_id' => '1',
        '_index' => 'test',
        '_source' => [
            'title' => 'foo',
        ],
        '_score' => 1.3,
        'fields' => [
            'created_at' => ['2026-01-01T00:00:00.000Z'],
        ],
        'sort' => [1.3, '1'],
        'matched_queries' => ['title_match'],
        '_explanation' => [
            'value' => 1.3,
            'description' => 'score explanation',
        ],
        'highlight' => [
            'title' => [
                ' <em>foo</em> ',
            ],
        ],
        'inner_hits' => [
            'nested' => [
                'hits' => [
                    'total' => [
                        'value' => 1,
                    ],
                    'hits' => [
                        [
                            '_id' => '2',
                            '_index' => 'test',
                            '_source' => [
                                'name' => 'bar',
                            ],
                            '_score' => 1.6,
                        ],
                    ],
                ],
            ],
        ],
    ]);
});

it('retrieves index name', function () {
    $this->assertSame('test', $this->hit->index());
});

it('creates a fake hit from source', function () {
    $hit = Hit::fake(['title' => 'foo'], index: 'posts', id: '123', score: 4.2);

    expect($hit->index())->toBe('posts')
        ->and($hit->id())->toBe('123')
        ->and($hit->score())->toBe(4.2)
        ->and($hit->source())->toBe(['title' => 'foo']);
});

it('creates a fake hit from a document', function () {
    $hit = Hit::fake(Document::fake('123', ['title' => 'foo']), index: 'posts');

    expect($hit->index())->toBe('posts')
        ->and($hit->id())->toBe('123')
        ->and($hit->source())->toBe(['title' => 'foo']);
});

it('creates a fake hit from raw hit attributes', function () {
    $hit = Hit::fake([
        '_index' => 'articles',
        '_id' => 'post-1',
        '_score' => 4.2,
        '_source' => [
            'title' => 'First',
        ],
    ]);

    expect($hit->index())->toBe('articles')
        ->and($hit->id())->toBe('post-1')
        ->and($hit->score())->toBe(4.2);
});

it('retrieves document', function () {
    $this->assertEquals(
        new Document('1', ['title' => 'foo']),
        $this->hit->document()
    );
});

it('retrieves highlight if present', function () {
    $this->assertEquals(
        new Highlight(['title' => [' <em>foo</em> ']]),
        $this->hit->highlight()
    );
});

it('returns null when a highlight is absent', function () {
    $hit = new Hit(['_id' => '1']);

    $this->assertNull($hit->highlight());
});

it('retrieves score', function () {
    $this->assertSame(1.3, $this->hit->score());
});

it('retrieves document identifier', function () {
    $this->assertSame('1', $this->hit->id());
});

it('retrieves source', function () {
    $this->assertSame(['title' => 'foo'], $this->hit->source());
});

it('retrieves fields', function () {
    $this->assertSame([
        'created_at' => ['2026-01-01T00:00:00.000Z'],
    ], $this->hit->fields());
});

it('retrieves sort values', function () {
    $this->assertSame([1.3, '1'], $this->hit->sort());
});

it('retrieves matched queries', function () {
    $this->assertSame(['title_match'], $this->hit->matchedQueries());
});

it('retrieves explanation', function () {
    $this->assertSame([
        'value' => 1.3,
        'description' => 'score explanation',
    ], $this->hit->explanation());
});

it('retrieves inner hits', function () {
    $innerHit = new Hit([
        '_id' => '2',
        '_index' => 'test',
        '_source' => [
            'name' => 'bar',
        ],
        '_score' => 1.6,
    ]);

    $nestedInnerHits = $this->hit->innerHits()['nested'];

    $this->assertCount(1, $nestedInnerHits);
    $this->assertEquals($innerHit, $nestedInnerHits[0]);
});

it('retrieves raw representation', function () {
    $this->assertSame([
        '_id' => '1',
        '_index' => 'test',
        '_source' => [
            'title' => 'foo',
        ],
        '_score' => 1.3,
        'fields' => [
            'created_at' => ['2026-01-01T00:00:00.000Z'],
        ],
        'sort' => [1.3, '1'],
        'matched_queries' => ['title_match'],
        '_explanation' => [
            'value' => 1.3,
            'description' => 'score explanation',
        ],
        'highlight' => [
            'title' => [
                ' <em>foo</em> ',
            ],
        ],
        'inner_hits' => [
            'nested' => [
                'hits' => [
                    'total' => [
                        'value' => 1,
                    ],
                    'hits' => [
                        [
                            '_id' => '2',
                            '_index' => 'test',
                            '_source' => [
                                'name' => 'bar',
                            ],
                            '_score' => 1.6,
                        ],
                    ],
                ],
            ],
        ],
    ], $this->hit->raw());
});
