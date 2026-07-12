<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\Settings;

it('sets top level settings group', function () {
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

it('sets index settings', function () {
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

it('sets analysis settings', function () {
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

it('sets similarity settings', function () {
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

it('casts default values to an empty array', function () {
    $this->assertSame([], (new Settings)->toArray());
});

it('casts configured values to an array', function () {
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
