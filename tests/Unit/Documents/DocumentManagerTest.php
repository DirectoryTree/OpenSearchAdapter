<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Documents;

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManager;
use DirectoryTree\OpenSearchAdapter\Documents\Routing;
use DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use OpenSearch\Client;
use stdClass;

beforeEach(function () {
    $this->client = $this->createMock(Client::class);
    $this->documentManager = new DocumentManager($this->client);
});

test('documents can be indexed with refresh', function () {
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

test('documents can be indexed without refresh', function () {
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

test('documents can be indexed with custom routing', function () {
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

    $routing = (new Routing)
        ->add('1', 'Doc1')
        ->add('2', 'Doc2');

    $this->assertSame($this->documentManager, $this->documentManager->index('test', $documents, true, $routing));
});

test('documents can be deleted with refresh', function () {
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

    $documentIds = ['1', '2'];

    $this->assertSame($this->documentManager, $this->documentManager->delete('test', $documentIds, true));
});

test('documents can be deleted without refresh', function () {
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

    $documentIds = ['1'];

    $this->assertSame($this->documentManager, $this->documentManager->delete('test', $documentIds, false));
});

test('documents can be deleted with custom routing', function () {
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

    $documentIds = ['1', '2'];

    $routing = (new Routing)
        ->add('1', 'Doc1')
        ->add('2', 'Doc2');

    $this->assertSame($this->documentManager, $this->documentManager->delete('test', $documentIds, true, $routing));
});

test('documents can be deleted by query with refresh', function () {
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

test('documents can be deleted by query without refresh', function () {
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

test('documents can be found', function () {
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

test('exception is thrown when index operation was unsuccessful', function () {
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
