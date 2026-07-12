<?php

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManager;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentRouting;
use DirectoryTree\OpenSearchAdapter\Indices\Alias;
use DirectoryTree\OpenSearchAdapter\Indices\AliasActions;
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

    $index = sprintf('adapter_integration_%s', bin2hex(random_bytes(4)));
    $aliasName = $index.'_alias';

    try {
        $indexManager->create(new IndexBlueprint($index));

        expect($indexManager->exists($index))->toBeTrue();

        $documentManager->index($index, [
            new Document('1', ['title' => 'Laravel OpenSearch adapter', 'status' => 'published']),
            new Document('2', ['title' => 'Draft document', 'status' => 'draft']),
        ], refresh: true);

        $response = $documentManager->search($index, new SearchRequest([
            'match' => [
                'title' => 'Laravel',
            ],
        ]));

        expect($response->total())->toBe(1)
            ->and($response->hits()[0]->document()->id())->toBe('1')
            ->and($response->hits()[0]->document()->get('status'))->toBe('published');

        $indexManager->putAlias($index, new Alias($aliasName, [
            'term' => ['status' => 'published'],
        ]));

        $aliases = $indexManager->getAliases($index);

        expect($aliases)->toHaveKey($aliasName)
            ->and($aliases[$aliasName]->filter())->toBe(['term' => ['status' => 'published']]);

        $indexManager->deleteAlias($index, $aliasName);

        expect($indexManager->getAliases($index))->not->toHaveKey($aliasName);

        $documentManager->delete($index, ['1'], refresh: true);

        expect($documentManager->search($index, new SearchRequest([
            'match' => [
                'title' => 'Laravel',
            ],
        ]))->total())->toBe(0);
    } finally {
        if ($indexManager->exists($index)) {
            $indexManager->delete($index);
        }
    }
});

it('handles mappings settings routing delete by query and rich search responses against opensearch', function (): void {
    $client = openSearchClient();
    $indexManager = new IndexManager($client);
    $documentManager = new DocumentManager($client);

    $index = sprintf('adapter_integration_%s', bin2hex(random_bytes(4)));

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
        $indexManager->create(new IndexBlueprint($index, $mapping, $settings));

        $indexManager->putMapping($index, (new Mapping)->keyword('category'));

        $documentManager->index($index, [
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
        ], refresh: true, routing: DocumentRouting::make('3', 'tenant-beta'));

        $response = $documentManager->search($index, (new SearchRequest([
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

        $documentManager->deleteByQuery($index, [
            'term' => [
                'status' => 'draft',
            ],
        ], refresh: true);

        expect($documentManager->search($index, new SearchRequest([
            'term' => [
                'status' => 'draft',
            ],
        ]))->total())->toBe(0);

        $documentManager->delete($index, ['3'], refresh: true, routing: DocumentRouting::make('3', 'tenant-beta'));

        expect($documentManager->search($index, new SearchRequest([
            'term' => [
                'tenant' => 'beta',
            ],
        ]))->total())->toBe(0);
    } finally {
        if ($indexManager->exists($index)) {
            $indexManager->delete($index);
        }
    }
});

it('atomically switches an alias between physical indexes', function (): void {
    $client = openSearchClient();
    $indices = new IndexManager($client);

    $prefix = sprintf('adapter_integration_%s', bin2hex(random_bytes(4)));
    $blue = $prefix.'_blue';
    $green = $prefix.'_green';
    $alias = $prefix.'_alias';

    try {
        $indices->create(new IndexBlueprint($blue));
        $indices->create(new IndexBlueprint($green));
        $indices->putAlias($blue, new Alias($alias, isWriteIndex: true));

        $indices->updateAliases(
            (new AliasActions)
                ->remove($blue, $alias)
                ->add($green, new Alias($alias, isWriteIndex: true)),
        );

        expect($indices->getAliases($blue))->not->toHaveKey($alias)
            ->and($indices->getAliases($green))->toHaveKey($alias)
            ->and($indices->getAliases($green)[$alias]->isWriteIndex())->toBeTrue();
    } finally {
        if ($indices->exists($blue)) {
            $indices->delete($blue);
        }

        if ($indices->exists($green)) {
            $indices->delete($green);
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
