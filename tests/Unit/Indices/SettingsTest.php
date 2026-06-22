<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Settings;

test('top level settings group can be set', function () {
    $settings = (new Settings)->set('analysis', [
        'analyzer' => [
            'content' => [
                'type' => 'custom',
                'tokenizer' => 'whitespace',
            ],
        ],
    ]);

    $this->assertSame([
        'analysis' => [
            'analyzer' => [
                'content' => [
                    'type' => 'custom',
                    'tokenizer' => 'whitespace',
                ],
            ],
        ],
    ], $settings->toArray());
});

test('index settings can be set', function () {
    $settings = (new Settings)->index([
        'number_of_replicas' => 2,
        'refresh_interval' => -1,
    ]);

    $this->assertSame([
        'index' => [
            'number_of_replicas' => 2,
            'refresh_interval' => -1,
        ],
    ], $settings->toArray());
});

test('analysis settings can be set', function () {
    $settings = (new Settings)->analysis([
        'analyzer' => [
            'content' => [
                'type' => 'custom',
                'tokenizer' => 'whitespace',
            ],
        ],
    ]);

    $this->assertSame([
        'analysis' => [
            'analyzer' => [
                'content' => [
                    'type' => 'custom',
                    'tokenizer' => 'whitespace',
                ],
            ],
        ],
    ], $settings->toArray());
});

test('similarity settings can be set', function () {
    $settings = (new Settings)->similarity([
        'default' => [
            'type' => 'BM25',
        ],
    ]);

    $this->assertSame([
        'similarity' => [
            'default' => [
                'type' => 'BM25',
            ],
        ],
    ], $settings->toArray());
});

test('default array casting', function () {
    $this->assertSame([], (new Settings)->toArray());
});

test('configured array casting', function () {
    $settings = (new Settings)
        ->index([
            'number_of_replicas' => 2,
            'refresh_interval' => -1,
        ])
        ->analysis([
            'analyzer' => [
                'content' => [
                    'type' => 'custom',
                    'tokenizer' => 'whitespace',
                ],
            ],
        ]);

    $this->assertSame([
        'index' => [
            'number_of_replicas' => 2,
            'refresh_interval' => -1,
        ],
        'analysis' => [
            'analyzer' => [
                'content' => [
                    'type' => 'custom',
                    'tokenizer' => 'whitespace',
                ],
            ],
        ],
    ], $settings->toArray());
});
