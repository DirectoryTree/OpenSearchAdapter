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
    $index = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('open')
        ->with([
            'index' => $index,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->open($index));
});

test('index can be closed', function () {
    $index = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('close')
        ->with([
            'index' => $index,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->close($index));
});

test('index existence can be checked', function () {
    $index = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('exists')
        ->with([
            'index' => $index,
        ])
        ->willReturn(true);

    $this->assertTrue($this->indexManager->exists($index));
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

test('mapping can be updated', function () {
    $index = 'foo';
    $mapping = (new Mapping)->text('bar');

    $this->indices
        ->expects($this->once())
        ->method('putMapping')
        ->with([
            'index' => $index,
            'body' => [
                'properties' => [
                    'bar' => [
                        'type' => 'text',
                    ],
                ],
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->putMapping($index, $mapping));
});

test('settings can be updated', function () {
    $index = 'foo';
    $settings = (new Settings)->index(['number_of_replicas' => 2]);

    $this->indices
        ->expects($this->once())
        ->method('putSettings')
        ->with([
            'index' => $index,
            'body' => [
                'settings' => [
                    'index' => [
                        'number_of_replicas' => 2,
                    ],
                ],
            ],
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->putSettings($index, $settings));
});

test('index can be deleted', function () {
    $index = 'foo';

    $this->indices
        ->expects($this->once())
        ->method('delete')
        ->with([
            'index' => $index,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->delete($index));
});

test('aliases can be retrieved', function () {
    $index = 'foo';
    $aliasName = 'bar';

    $this->indices
        ->expects($this->once())
        ->method('getAlias')
        ->with([
            'index' => $index,
        ])
        ->willReturn([
            $index => [
                'aliases' => [
                    $aliasName => [],
                ],
            ],
        ]);

    $this->assertEquals(
        [$aliasName => new Alias($aliasName)],
        $this->indexManager->getAliases($index)
    );
});

test('alias can be created', function () {
    $index = 'foo';
    $alias = (new Alias('bar', ['term' => ['user_id' => 12]], '12'));

    $this->indices
        ->expects($this->once())
        ->method('putAlias')
        ->with([
            'index' => $index,
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

    $this->assertSame($this->indexManager, $this->indexManager->putAlias($index, $alias));
});

test('alias can be deleted', function () {
    $index = 'foo';
    $aliasName = 'bar';

    $this->indices
        ->expects($this->once())
        ->method('deleteAlias')
        ->with([
            'index' => $index,
            'name' => $aliasName,
        ]);

    $this->assertSame($this->indexManager, $this->indexManager->deleteAlias($index, $aliasName));
});
