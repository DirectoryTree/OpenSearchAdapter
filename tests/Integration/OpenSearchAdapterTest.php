<?php

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManager;
use DirectoryTree\OpenSearchAdapter\Documents\Routing;
use DirectoryTree\OpenSearchAdapter\Indices\Alias;
use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\IndexManager;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;
use OpenSearch\Client;
use OpenSearch\GuzzleClientFactory;

it('manages indices documents aliases and searches against opensearch', function (): void {
    $client = openSearchClient();
    $indexManager = new IndexManager($client);
    $documentManager = new DocumentManager($client);

    $indexName = sprintf('adapter_integration_%s', bin2hex(random_bytes(4)));
    $aliasName = $indexName.'_alias';

    try {
        $indexManager->create(new IndexBlueprint($indexName));

        expect($indexManager->exists($indexName))->toBeTrue();

        $documentManager->index($indexName, [
            new Document('1', ['title' => 'Laravel OpenSearch adapter', 'status' => 'published']),
            new Document('2', ['title' => 'Draft document', 'status' => 'draft']),
        ], refresh: true);

        $response = $documentManager->search($indexName, new SearchRequest([
            'match' => [
                'title' => 'Laravel',
            ],
        ]));

        expect($response->total())->toBe(1)
            ->and($response->hits()[0]->document()->id())->toBe('1')
            ->and($response->hits()[0]->document()->content('status'))->toBe('published');

        $indexManager->putAlias($indexName, new Alias($aliasName, [
            'term' => ['status' => 'published'],
        ]));

        $aliases = $indexManager->getAliases($indexName);

        expect($aliases)->toHaveKey($aliasName)
            ->and($aliases[$aliasName]->filter())->toBe(['term' => ['status' => 'published']]);

        $indexManager->deleteAlias($indexName, $aliasName);

        expect($indexManager->getAliases($indexName))->not->toHaveKey($aliasName);

        $documentManager->delete($indexName, ['1'], refresh: true);

        expect($documentManager->search($indexName, new SearchRequest([
            'match' => [
                'title' => 'Laravel',
            ],
        ]))->total())->toBe(0);
    } finally {
        if ($indexManager->exists($indexName)) {
            $indexManager->drop($indexName);
        }
    }
});

it('handles mappings settings routing delete by query and rich search responses against opensearch', function (): void {
    $client = openSearchClient();
    $indexManager = new IndexManager($client);
    $documentManager = new DocumentManager($client);

    $indexName = sprintf('adapter_integration_%s', bin2hex(random_bytes(4)));

    $mapping = (new Mapping)
        ->text('title')
        ->keyword('status')
        ->keyword('tenant')
        ->integer('views');

    $settings = (new Settings)->index([
        'number_of_shards' => 1,
        'number_of_replicas' => 0,
    ]);

    try {
        $indexManager->create(new IndexBlueprint($indexName, $mapping, $settings));

        $indexManager->putMappingRaw($indexName, [
            'properties' => [
                'category' => ['type' => 'keyword'],
            ],
        ]);

        $documentManager->index($indexName, [
            new Document('1', [
                'title' => 'Laravel OpenSearch adapter',
                'status' => 'published',
                'tenant' => 'alpha',
                'views' => 10,
                'category' => 'packages',
            ]),
            new Document('2', [
                'title' => 'OpenSearch draft notes',
                'status' => 'draft',
                'tenant' => 'alpha',
                'views' => 3,
                'category' => 'notes',
            ]),
            new Document('3', [
                'title' => 'OpenSearch routed document',
                'status' => 'published',
                'tenant' => 'beta',
                'views' => 7,
                'category' => 'packages',
            ]),
        ], refresh: true, routing: (new Routing)->add('3', 'tenant-beta'));

        $response = $documentManager->search($indexName, (new SearchRequest([
            'match' => [
                'title' => 'OpenSearch',
            ],
        ]))
            ->highlight([
                'fields' => [
                    'title' => new stdClass,
                ],
            ])
            ->aggregations([
                'by_status' => [
                    'terms' => [
                        'field' => 'status',
                    ],
                ],
            ])
            ->sort([
                ['views' => 'desc'],
            ])
            ->source(['title', 'status', 'views'])
            ->trackTotalHits(true));

        expect($response->total())->toBe(3)
            ->and($response->hits()[0]->document()->id())->toBe('1')
            ->and($response->hits()[0]->highlight()?->snippets('title'))->not->toBeEmpty()
            ->and($response->aggregations()['by_status']->buckets()[0]->key())->toBe('published');

        $documentManager->deleteByQuery($indexName, [
            'term' => [
                'status' => 'draft',
            ],
        ], refresh: true);

        expect($documentManager->search($indexName, new SearchRequest([
            'term' => [
                'status' => 'draft',
            ],
        ]))->total())->toBe(0);

        $documentManager->delete($indexName, ['3'], refresh: true, routing: (new Routing)->add('3', 'tenant-beta'));

        expect($documentManager->search($indexName, new SearchRequest([
            'term' => [
                'tenant' => 'beta',
            ],
        ]))->total())->toBe(0);
    } finally {
        if ($indexManager->exists($indexName)) {
            $indexManager->drop($indexName);
        }
    }
});

/**
 * Create an OpenSearch client for integration tests.
 */
function openSearchClient(): Client
{
    return (new GuzzleClientFactory)->create([
        'base_uri' => getenv('OPENSEARCH_HOST') ?: 'http://127.0.0.1:9200',
    ]);
}
