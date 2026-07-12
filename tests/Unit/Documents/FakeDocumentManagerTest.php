<?php

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManagerInterface;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentRouting;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use DirectoryTree\OpenSearchAdapter\Search\SearchResponse;
use DirectoryTree\OpenSearchAdapter\Testing\Fakes\FakeDocumentManager;

it('implements the document manager contract', function () {
    expect(new FakeDocumentManager)->toBeInstanceOf(DocumentManagerInterface::class);
});

it('records indexed documents', function () {
    $documents = [
        new Document('1', ['title' => 'First']),
        new Document('2', ['title' => 'Second']),
    ];

    $routing = DocumentRouting::make('1', 'tenant-1')
        ->add('2', 'tenant-2');

    $documentsManager = new FakeDocumentManager;

    $documentsManager->index('posts', $documents, true, $routing);

    $documentsManager->assertIndexed('posts', $documents, true, $routing);
});

it('records deleted documents', function () {
    $routing = DocumentRouting::make('1', 'tenant-1')
        ->add('2', 'tenant-2');

    $documents = new FakeDocumentManager;

    $documents->delete('posts', ['1', '2'], true, $routing);

    $documents->assertDeleted('posts', ['1', '2'], true, $routing);
});

it('records delete-by-query operations', function () {
    $documents = new FakeDocumentManager;

    $documents->deleteByQuery('posts', $query = [
        'term' => [
            'status' => 'draft',
        ],
    ], true);

    $documents->assertDeletedByQuery('posts', $query, true);
});

it('records searches and returns configured responses', function () {
    $request = new SearchRequest([
        'match' => [
            'title' => 'OpenSearch',
        ],
    ]);

    $response = new SearchResponse([
        'hits' => [
            'total' => [
                'value' => 1,
                'relation' => 'eq',
            ],
            'hits' => [
                [
                    '_id' => '1',
                    '_index' => 'posts',
                    '_source' => [
                        'title' => 'OpenSearch',
                    ],
                ],
            ],
        ],
    ]);

    $documents = new FakeDocumentManager($response);

    $result = $documents->search('posts', $request);

    expect($result)->toBe($response);

    $documents->assertSearched('posts', $request);
});

it('creates a fake document manager with a search response', function () {
    $documents = new FakeDocumentManager($response = SearchResponse::fake());

    expect($documents->search('posts', new SearchRequest))->toBe($response);
});
