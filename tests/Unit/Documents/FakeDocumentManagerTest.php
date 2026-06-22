<?php

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManagerInterface;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentRouting;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use DirectoryTree\OpenSearchAdapter\Search\SearchResponse;
use DirectoryTree\OpenSearchAdapter\Testing\Fakes\FakeDocumentManager;

test('fake document manager implements the document manager contract', function () {
    expect(new FakeDocumentManager)->toBeInstanceOf(DocumentManagerInterface::class);
});

test('fake document manager records indexed documents', function () {
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

test('fake document manager records deleted documents', function () {
    $routing = DocumentRouting::make('1', 'tenant-1')
        ->add('2', 'tenant-2');

    $documents = new FakeDocumentManager;

    $documents->delete('posts', ['1', '2'], true, $routing);

    $documents->assertDeleted('posts', ['1', '2'], true, $routing);
});

test('fake document manager records delete by query operations', function () {
    $documents = new FakeDocumentManager;

    $documents->deleteByQuery('posts', $query = [
        'term' => [
            'status' => 'draft',
        ],
    ], true);

    $documents->assertDeletedByQuery('posts', $query, true);
});

test('fake document manager records searches and returns configured responses', function () {
    $documents = new FakeDocumentManager;
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

    $result = $documents
        ->respondWith($response)
        ->search('posts', $request);

    expect($result)->toBe($response);

    $documents->assertSearched('posts', $request);
});
