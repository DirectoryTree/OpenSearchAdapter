<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Alias;
use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\IndexManager;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;
use OpenSearch\Client;
use OpenSearch\Namespaces\IndicesNamespace;

beforeEach(function () {
    $client = $this->createMock(Client::class);
    $this->indices = $this->createMock(IndicesNamespace::class);

    $client
        ->method('indices')
        ->willReturn($this->indices);

    $this->indexManager = new IndexManager($client);
});

test('index can be opened', function () {
    $indexName = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('open')
        ->with([
            'index' => $indexName,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->open($indexName));
});

test('index can be closed', function () {
    $indexName = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('close')
        ->with([
            'index' => $indexName,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->close($indexName));
});

test('index existence can be checked', function () {
    $indexName = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('exists')
        ->with([
            'index' => $indexName,
        ])
        ->willReturn(true);

    $this->assertTrue($this->indexManager->exists($indexName));
});

test('index can be created without mapping and settings', function () {
    $index = new IndexBlueprint('foo');

    $this->indices
        ->expects($this->once())
        ->method('create')
        ->with([
            'index' => $index->name(),
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->create($index));
});

test('index can be created without mapping', function () {
    $settings = (new Settings)->index(['number_of_replicas' => 2]);
    $index = new IndexBlueprint('foo', null, $settings);

    $this->indices
        ->expects($this->once())
        ->method('create')
        ->with([
            'index' => $index->name(),
            'body' => [
                'settings' => [
                    'index' => [
                        'number_of_replicas' => 2,
                    ],
                ],
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->create($index));
});

test('index can be created without settings', function () {
    $mapping = (new Mapping)->text('foo');
    $index = new IndexBlueprint('bar', $mapping);

    $this->indices
        ->expects($this->once())
        ->method('create')
        ->with([
            'index' => $index->name(),
            'body' => [
                'mappings' => [
                    'properties' => [
                        'foo' => [
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->create($index));
});

test('index can be created with empty settings and mapping', function () {
    $index = new IndexBlueprint('foo', new Mapping, new Settings);

    $this->indices
        ->expects($this->once())
        ->method('create')
        ->with([
            'index' => $index->name(),
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->create($index));
});

test('index can be created with raw mapping and settings', function () {
    $indexName = 'foo';
    $mapping = ['properties' => ['bar' => ['type' => 'text']]];
    $settings = ['index' => ['number_of_replicas' => 2]];

    $this->indices
        ->expects($this->once())
        ->method('create')
        ->with([
            'index' => $indexName,
            'body' => [
                'mappings' => $mapping,
                'settings' => $settings,
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->createRaw($indexName, $mapping, $settings));
});

test('mapping can be updated', function () {
    $indexName = 'foo';
    $mapping = (new Mapping)->text('bar');

    $this->indices
        ->expects($this->once())
        ->method('putMapping')
        ->with([
            'index' => $indexName,
            'body' => [
                'properties' => [
                    'bar' => [
                        'type' => 'text',
                    ],
                ],
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->putMapping($indexName, $mapping));
});

test('mapping can be updated with raw data', function () {
    $indexName = 'foo';
    $mapping = ['properties' => ['bar' => ['type' => 'text']]];

    $this->indices
        ->expects($this->once())
        ->method('putMapping')
        ->with([
            'index' => $indexName,
            'body' => $mapping,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->putMappingRaw($indexName, $mapping));
});

test('settings can be updated', function () {
    $indexName = 'foo';
    $settings = (new Settings)->index(['number_of_replicas' => 2]);

    $this->indices
        ->expects($this->once())
        ->method('putSettings')
        ->with([
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'index' => [
                        'number_of_replicas' => 2,
                    ],
                ],
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->putSettings($indexName, $settings));
});

test('settings can be updated with raw data', function () {
    $indexName = 'foo';
    $settings = ['index' => ['number_of_replicas' => 2]];

    $this->indices
        ->expects($this->once())
        ->method('putSettings')
        ->with([
            'index' => $indexName,
            'body' => [
                'settings' => $settings,
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->putSettingsRaw($indexName, $settings));
});

test('index can be dropped', function () {
    $indexName = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('delete')
        ->with([
            'index' => $indexName,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->drop($indexName));
});

test('aliases can be retrieved', function () {
    $indexName = 'foo';
    $aliasName = 'bar';

    $this->indices
        ->expects($this->once())
        ->method('getAlias')
        ->with([
            'index' => $indexName,
        ])
        ->willReturn([
            $indexName => [
                'aliases' => [
                    $aliasName => [],
                ],
            ],
        ]);

    $this->assertEquals(
        collect([$aliasName => new Alias($aliasName)]),
        $this->indexManager->getAliases($indexName)
    );
});

test('alias can be created', function () {
    $indexName = 'foo';
    $alias = (new Alias('bar', ['term' => ['user_id' => 12]], '12'));

    $this->indices
        ->expects($this->once())
        ->method('putAlias')
        ->with([
            'index' => $indexName,
            'name' => $alias->name(),
            'body' => [
                'routing' => '12',
                'filter' => [
                    'term' => [
                        'user_id' => 12,
                    ],
                ],
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->putAlias($indexName, $alias));
});

test('alias can be deleted', function () {
    $indexName = 'foo';
    $aliasName = 'bar';

    $this->indices
        ->expects($this->once())
        ->method('deleteAlias')
        ->with([
            'index' => $indexName,
            'name' => $aliasName,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->deleteAlias($indexName, $aliasName));
});
