<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Documents;

use DirectoryTree\OpenSearchAdapter\Documents\Document;

test('document getters', function () {
    $document = new Document('123456', ['title' => 'book', 'price' => 10]);

    $this->assertSame('123456', $document->id());
    $this->assertSame(['title' => 'book', 'price' => 10], $document->content());
    $this->assertSame('book', $document->content('title'));
});

test('array casting', function () {
    $document = new Document('1', ['title' => 'test']);

    $this->assertSame([
        'id' => '1',
        'content' => ['title' => 'test'],
    ], $document->toArray());
});
