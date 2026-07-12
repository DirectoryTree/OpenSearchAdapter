<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Alias;
use DirectoryTree\OpenSearchAdapter\Indices\AliasActions;

it('builds alias actions', function () {
    $actions = (new AliasActions)
        ->remove('posts_blue', 'posts')
        ->add('posts_green', new Alias('posts', isWriteIndex: true))
        ->removeIndex('posts_retired');

    expect($actions->actions())->toBe([
        [
            'remove' => [
                'index' => 'posts_blue',
                'alias' => 'posts',
            ],
        ],
        [
            'add' => [
                'index' => 'posts_green',
                'alias' => 'posts',
                'is_write_index' => true,
            ],
        ],
        [
            'remove_index' => [
                'index' => 'posts_retired',
            ],
        ],
    ])->and($actions->toArray())->toBe([
        'actions' => $actions->actions(),
    ]);
});

it('includes alias routing and filters in add actions', function () {
    $actions = (new AliasActions)->add(
        'posts',
        new Alias(
            name: 'published_posts',
            filter: ['term' => ['status' => 'published']],
            routing: 'tenant-1',
            isWriteIndex: false,
        ),
    );

    expect($actions->toArray())->toBe([
        'actions' => [
            [
                'add' => [
                    'index' => 'posts',
                    'alias' => 'published_posts',
                    'routing' => 'tenant-1',
                    'filter' => ['term' => ['status' => 'published']],
                    'is_write_index' => false,
                ],
            ],
        ],
    ]);
});
