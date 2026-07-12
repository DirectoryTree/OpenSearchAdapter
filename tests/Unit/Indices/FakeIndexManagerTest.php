<?php

use DirectoryTree\OpenSearchAdapter\Indices\Alias;
use DirectoryTree\OpenSearchAdapter\Indices\AliasActions;
use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\IndexManagerInterface;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;
use DirectoryTree\OpenSearchAdapter\Testing\Fakes\FakeIndexManager;

test('fake index manager implements the index manager contract', function () {
    expect(new FakeIndexManager)->toBeInstanceOf(IndexManagerInterface::class);
});

test('fake index manager tracks index existence', function () {
    $indices = new FakeIndexManager;

    expect($indices->exists('posts'))->toBeFalse();

    $indices->assertChecked('posts');

    $indices = new FakeIndexManager(existing: ['posts']);

    expect($indices->exists('posts'))->toBeTrue();
});

test('fake index manager records created indices', function () {
    $indices = new FakeIndexManager;

    $indices->create($index = new IndexBlueprint(
        'posts',
        (new Mapping)->text('title'),
        (new Settings)->index(['number_of_replicas' => 0])
    ));

    $indices->assertCreated($index);

    expect($indices->exists('posts'))->toBeTrue();
});

test('fake index manager records mapping updates', function () {
    $indices = new FakeIndexManager;

    $indices->putMapping('posts', $mapping = (new Mapping)->keyword('status'));

    $indices->assertMappingPut('posts', $mapping);
});

test('fake index manager records settings updates', function () {
    $indices = new FakeIndexManager;

    $indices->putSettings('posts', $settings = (new Settings)->index(['refresh_interval' => -1]));

    $indices->assertSettingsPut('posts', $settings);
});

test('fake index manager records open and close operations', function () {
    $indices = new FakeIndexManager;

    $indices
        ->close('posts')
        ->open('posts')
        ->assertClosed('posts')
        ->assertOpened('posts');
});

test('fake index manager records deleted indices', function () {
    $indices = new FakeIndexManager(existing: ['posts']);

    $indices->delete('posts');

    $indices->assertDeleted('posts');

    expect($indices->exists('posts'))->toBeFalse();
});

test('fake index manager records aliases', function () {
    $indices = new FakeIndexManager;

    $alias = new Alias('published_posts', [
        'term' => ['status' => 'published'],
    ], 'tenant-1');

    $indices->putAlias('posts', $alias);

    $indices->assertAliasPut('posts', $alias);

    expect($indices->getAliases('posts'))->toBe([
        'published_posts' => $alias,
    ]);
});

test('fake index manager records deleted aliases', function () {
    $indices = new FakeIndexManager;

    $indices->putAlias('posts', new Alias('published_posts'));

    $indices->deleteAlias('posts', 'published_posts');

    $indices->assertAliasDeleted('posts', 'published_posts');

    expect($indices->getAliases('posts'))->toBe([]);
});

test('fake index manager applies atomic alias updates', function () {
    $indices = new FakeIndexManager(existing: [
        'posts_blue',
        'posts_green',
        'posts_retired',
    ]);

    $indices->putAlias('posts_blue', new Alias('posts', isWriteIndex: true));

    $actions = (new AliasActions)
        ->remove('posts_blue', 'posts')
        ->add('posts_green', new Alias('posts', isWriteIndex: true))
        ->removeIndex('posts_retired');

    $indices->updateAliases($actions);

    $indices
        ->assertAliasesUpdated($actions)
        ->assertAliasDeleted('posts_blue', 'posts')
        ->assertAliasPut('posts_green', new Alias('posts', isWriteIndex: true))
        ->assertDeleted('posts_retired');

    expect($indices->getAliases('posts_blue'))->toBe([])
        ->and($indices->getAliases('posts_green'))->toEqual([
            'posts' => new Alias('posts', isWriteIndex: true),
        ])
        ->and($indices->exists('posts_retired'))->toBeFalse();
});
