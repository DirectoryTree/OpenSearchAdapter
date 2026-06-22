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

test('index name can be retrieved', function () {
    $this->assertSame('test', $this->hit->index());
});

test('document can be retrieved', function () {
    $this->assertEquals(
        new Document('1', ['title' => 'foo']),
        $this->hit->document()
    );
});

test('highlight can be retrieved if present', function () {
    $this->assertEquals(
        new Highlight(['title' => [' <em>foo</em> ']]),
        $this->hit->highlight()
    );
});

test('nothing is returned when trying to retrieve highlight but it is not present', function () {
    $hit = new Hit(['_id' => '1']);

    $this->assertNull($hit->highlight());
});

test('score can be retrieved', function () {
    $this->assertSame(1.3, $this->hit->score());
});

test('document identifier can be retrieved', function () {
    $this->assertSame('1', $this->hit->id());
});

test('source can be retrieved', function () {
    $this->assertSame(['title' => 'foo'], $this->hit->source());
});

test('fields can be retrieved', function () {
    $this->assertSame([
        'created_at' => ['2026-01-01T00:00:00.000Z'],
    ], $this->hit->fields());
});

test('sort values can be retrieved', function () {
    $this->assertSame([1.3, '1'], $this->hit->sort());
});

test('matched queries can be retrieved', function () {
    $this->assertSame(['title_match'], $this->hit->matchedQueries());
});

test('explanation can be retrieved', function () {
    $this->assertSame([
        'value' => 1.3,
        'description' => 'score explanation',
    ], $this->hit->explanation());
});

test('inner hits can be retrieved', function () {
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

test('raw representation can be retrieved', function () {
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
