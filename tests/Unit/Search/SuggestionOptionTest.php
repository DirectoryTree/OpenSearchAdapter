<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\SuggestionOption;

test('suggestion option values can be retrieved', function () {
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

test('raw representation can be retrieved', function () {
    $option = new SuggestionOption([
        'text' => 'foo',
        'score' => 0.8,
    ]);

    $this->assertSame([
        'text' => 'foo',
        'score' => 0.8,
    ], $option->raw());
});
