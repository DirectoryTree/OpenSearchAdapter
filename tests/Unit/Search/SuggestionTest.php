<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Suggestion;

test('text can be retrieved', function () {
    $suggestion = new Suggestion(['text' => 'foo']);

    $this->assertSame('foo', $suggestion->text());
});

test('offset can be retrieved', function () {
    $suggestion = new Suggestion(['offset' => 0]);

    $this->assertSame(0, $suggestion->offset());
});

test('length can be retrieved', function () {
    $suggestion = new Suggestion(['length' => 5]);

    $this->assertSame(5, $suggestion->length());
});

test('options can be retrieved', function () {
    $suggestion = new Suggestion([
        'options' => [
            [
                'text' => 'foo',
                'score' => 0.8,
                'freq' => 1,
            ],
        ],
    ]);

    $this->assertEquals([
        [
            'text' => 'foo',
            'score' => 0.8,
            'freq' => 1,
        ],
    ], $suggestion->options());
});

test('raw representation can be retrieved', function () {
    $suggestion = new Suggestion([
        'text' => 'foo',
        'offset' => 0,
        'length' => 5,
        'options' => [],
    ]);

    $this->assertSame([
        'text' => 'foo',
        'offset' => 0,
        'length' => 5,
        'options' => [],
    ], $suggestion->raw());
});
