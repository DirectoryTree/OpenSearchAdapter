<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Documents;

use DirectoryTree\OpenSearchAdapter\Documents\Document;

test('document getters', function () {
    $document = new Document('123456', ['title' => 'book', 'price' => 10]);

    $this->assertSame('123456', $document->id());
    $this->assertSame(['title' => 'book', 'price' => 10], $document->source());
    $this->assertSame('book', $document->get('title'));
    $this->assertNull($document->get('missing'));
});

test('fake document can be created', function () {
    $document = Document::fake('123456', ['title' => 'book']);

    expect($document->id())->toBe('123456')
        ->and($document->source())->toBe(['title' => 'book']);
});

test('array casting', function () {
    $document = new Document('1', ['title' => 'test']);

    $this->assertSame([
        'id' => '1',
        'source' => ['title' => 'test'],
    ], $document->toArray());
});

test('bulk index payload can be retrieved', function () {
    $document = new Document('1', ['title' => 'test']);

    $this->assertSame([
        ['index' => ['_id' => '1']],
        ['title' => 'test'],
    ], $document->toBulkIndex());
});

test('bulk index payload can be retrieved with routing', function () {
    $document = new Document('1', ['title' => 'test']);

    $this->assertSame([
        ['index' => ['_id' => '1', 'routing' => 'tenant-1']],
        ['title' => 'test'],
    ], $document->toBulkIndex('tenant-1'));
});
