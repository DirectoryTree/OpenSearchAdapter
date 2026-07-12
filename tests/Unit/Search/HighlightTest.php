<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Highlight;

it('retrieves snippets for highlighted field', function () {
    $highlight = new Highlight([
        'message' => [
            ' with the <em>number</em>',
            '  <em>1</em>',
        ],
    ]);

    $this->assertEquals([
        ' with the <em>number</em>',
        '  <em>1</em>',
    ], $highlight->snippets('message'));
});

it('returns an empty array for a missing highlighted field', function () {
    $highlight = new Highlight([
        'foo' => [
            'test fragment',
        ],
    ]);

    $this->assertSame([], $highlight->snippets('bar'));
});

it('retrieves raw representation', function () {
    $highlight = new Highlight([
        'foo' => [
            'test fragment 1',
        ],
        'bar' => [
            'test fragment 2',
            'test fragment 3',
        ],
    ]);

    $this->assertSame([
        'foo' => [
            'test fragment 1',
        ],
        'bar' => [
            'test fragment 2',
            'test fragment 3',
        ],
    ], $highlight->raw());
});
