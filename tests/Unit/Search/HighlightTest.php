<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Highlight;

test('snippets can be retrieved for highlighted field', function () {
    $highlight = new Highlight([
        'message' => [
            ' with the <em>number</em>',
            '  <em>1</em>',
        ],
    ]);

    $this->assertEquals(collect([
        ' with the <em>number</em>',
        '  <em>1</em>',
    ]), $highlight->snippets('message'));
});

test('empty collection is returned when trying to retrieve snippets for non existing field', function () {
    $highlight = new Highlight([
        'foo' => [
            'test fragment',
        ],
    ]);

    $this->assertEquals(collect(), $highlight->snippets('bar'));
});

test('raw representation can be retrieved', function () {
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
