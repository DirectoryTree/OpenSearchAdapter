<?php

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManager;
use DirectoryTree\OpenSearchAdapter\Indices\Alias;
use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\IndexManager;
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

/**
 * Create an OpenSearch client for integration tests.
 */
function openSearchClient(): Client
{
    return (new GuzzleClientFactory)->create([
        'base_uri' => getenv('OPENSEARCH_HOST') ?: 'http://127.0.0.1:9200',
    ]);
}
