<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Suggestion;
use DirectoryTree\OpenSearchAdapter\Search\SuggestionOption;

it('retrieves text', function () {
    $suggestion = new Suggestion(['text' => 'foo']);

    $this->assertSame('foo', $suggestion->text());
});

it('retrieves offset', function () {
    $suggestion = new Suggestion(['offset' => 0]);

    $this->assertSame(0, $suggestion->offset());
});

it('retrieves length', function () {
    $suggestion = new Suggestion(['length' => 5]);

    $this->assertSame(5, $suggestion->length());
});

it('retrieves options', function () {
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
        new SuggestionOption([
            'text' => 'foo',
            'score' => 0.8,
            'freq' => 1,
        ]),
    ], $suggestion->options());
});

it('retrieves raw representation', function () {
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
