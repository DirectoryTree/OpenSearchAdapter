<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\SuggestionOption;

it('retrieves suggestion option values', function () {
    $option = new SuggestionOption([
        'text' => 'foo',
        'score' => 0.8,
        'highlighted' => '<em>foo</em>',
        'collate_match' => true,
        '_source' => ['title' => 'Foo'],
    ]);

    $this->assertSame('foo', $option->text());
    $this->assertSame(0.8, $option->score());
    $this->assertSame('<em>foo</em>', $option->highlighted());
    $this->assertTrue($option->collateMatch());
    $this->assertSame(['title' => 'Foo'], $option->source());
});

it('retrieves raw representation', function () {
    $option = new SuggestionOption([
        'text' => 'foo',
        'score' => 0.8,
    ]);

    $this->assertSame([
        'text' => 'foo',
        'score' => 0.8,
    ], $option->raw());
});
