<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Documents;

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManager;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentRouting;
use DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use OpenSearch\Client;
use stdClass;

beforeEach(function () {
    $this->client = $this->createMock(Client::class);
    $this->documentManager = new DocumentManager($this->client);
});

it('indexes documents with refresh', function () {
    $this->client
        ->expects($this->once())
        ->method('bulk')
        ->with([
            'index' => 'test',
            'refresh' => 'true',
            'body' => [
                ['index' => ['_id' => '1']],
                ['title' => 'Doc 1'],
                ['index' => ['_id' => '2']],
                ['title' => 'Doc 2'],
            ],
        ])
        ->willReturn([
            'took' => 0,
            'errors' => false,
            'items' => [],
        ]);

    $documents = [
        new Document('1', ['title' => 'Doc 1']),
        new Document('2', ['title' => 'Doc 2']),
    ];

    $this->assertSame($this->documentManager, $this->documentManager->index('test', $documents, true));
});

it('indexes documents without refresh', function () {
    $this->client
        ->expects($this->once())
        ->method('bulk')
        ->with([
            'index' => 'test',
            'refresh' => 'false',
            'body' => [
                ['index' => ['_id' => '1']],
                ['title' => 'Doc 1'],
            ],
        ])
        ->willReturn([
            'took' => 0,
            'errors' => false,
            'items' => [],
        ]);

    $documents = [
        new Document('1', ['title' => 'Doc 1']),
    ];

    $this->assertSame($this->documentManager, $this->documentManager->index('test', $documents, false));
});

it('indexes documents with custom routing', function () {
    $this->client
        ->expects($this->once())
        ->method('bulk')
        ->with([
            'index' => 'test',
            'refresh' => 'true',
            'body' => [
                ['index' => ['_id' => '1', 'routing' => 'Doc1']],
                ['title' => 'Doc 1'],
                ['index' => ['_id' => '2', 'routing' => 'Doc2']],
                ['title' => 'Doc 2'],
            ],
        ])
        ->willReturn([
            'took' => 0,
            'errors' => false,
            'items' => [],
        ]);

    $documents = [
        new Document('1', ['title' => 'Doc 1']),
        new Document('2', ['title' => 'Doc 2']),
    ];

    $routing = DocumentRouting::make('1', 'Doc1')
        ->add('2', 'Doc2');

    $this->assertSame($this->documentManager, $this->documentManager->index('test', $documents, true, $routing));
});

it('deletes documents with refresh', function () {
    $this->client
        ->expects($this->once())
        ->method('bulk')
        ->with([
            'index' => 'test',
            'refresh' => 'true',
            'body' => [
                ['delete' => ['_id' => '1']],
                ['delete' => ['_id' => '2']],
            ],
        ])
        ->willReturn([
            'took' => 0,
            'errors' => false,
            'items' => [],
        ]);

    $ids = ['1', '2'];

    $this->assertSame($this->documentManager, $this->documentManager->delete('test', $ids, true));
});

it('deletes documents without refresh', function () {
    $this->client
        ->expects($this->once())
        ->method('bulk')
        ->with([
            'index' => 'test',
            'refresh' => 'false',
            'body' => [
                ['delete' => ['_id' => '1']],
            ],
        ])
        ->willReturn([
            'took' => 0,
            'errors' => false,
            'items' => [],
        ]);

    $ids = ['1'];

    $this->assertSame($this->documentManager, $this->documentManager->delete('test', $ids, false));
});

it('deletes documents with custom routing', function () {
    $this->client
        ->expects($this->once())
        ->method('bulk')
        ->with([
            'index' => 'test',
            'refresh' => 'true',
            'body' => [
                ['delete' => ['_id' => '1', 'routing' => 'Doc1']],
                ['delete' => ['_id' => '2', 'routing' => 'Doc2']],
            ],
        ])
        ->willReturn([
            'took' => 0,
            'errors' => false,
            'items' => [],
        ]);

    $ids = ['1', '2'];

    $routing = DocumentRouting::make('1', 'Doc1')
        ->add('2', 'Doc2');

    $this->assertSame($this->documentManager, $this->documentManager->delete('test', $ids, true, $routing));
});

it('deletes documents by query with refresh', function () {
    $this->client
        ->expects($this->once())
        ->method('deleteByQuery')
        ->with([
            'index' => 'test',
            'refresh' => 'true',
            'body' => [
                'query' => ['match_all' => new stdClass],
            ],
        ]);

    $query = [
        'match_all' => new stdClass,
    ];

    $this->assertSame($this->documentManager, $this->documentManager->deleteByQuery('test', $query, true));
});

it('deletes documents by query without refresh', function () {
    $this->client
        ->expects($this->once())
        ->method('deleteByQuery')
        ->with([
            'index' => 'test',
            'refresh' => 'false',
            'body' => [
                'query' => ['match_all' => new stdClass],
            ],
        ]);

    $query = [
        'match_all' => new stdClass,
    ];

    $this->assertSame($this->documentManager, $this->documentManager->deleteByQuery('test', $query, false));
});

it('finds documents', function () {
    $this->client
        ->expects($this->once())
        ->method('search')
        ->with([
            'index' => 'test',
            'body' => [
                'query' => [
                    'match' => ['content' => 'foo'],
                ],
            ],
        ])
        ->willReturn([
            'hits' => [
                'total' => [
                    'value' => 1,
                    'relation' => 'eq',
                ],
                'max_score' => 1.601195,
                'hits' => [
                    [
                        '_index' => 'test',
                        '_id' => '1',
                        '_score' => 1.601195,
                        '_source' => ['content' => 'foo'],
                    ],
                ],
            ],
        ]);

    $response = $this->documentManager->search('test', new SearchRequest([
        'match' => ['content' => 'foo'],
    ]));

    $this->assertSame(1, $response->total());
    $this->assertEquals(new Document('1', ['content' => 'foo']), $response->hits()[0]->document());
});

it('throws an exception when an index operation is unsuccessful', function () {
    $this->client
        ->expects($this->once())
        ->method('bulk')
        ->with([
            'index' => 'test',
            'refresh' => 'false',
            'body' => [
                ['index' => ['_id' => '1']],
                ['title' => 'Doc 1'],
            ],
        ])
        ->willReturn([
            'took' => 0,
            'errors' => true,
            'items' => [],
        ]);

    $this->expectException(BulkRequestException::class);

    $documents = [
        new Document('1', ['title' => 'Doc 1']),
    ];

    $this->documentManager->index('test', $documents);
});
