<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use BadMethodCallException;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;

dataset('settings options', [
    [
        'option' => 'index',
        'configuration' => [
            'number_of_replicas' => 2,
        ],
        'expected' => [
            'index' => [
                'number_of_replicas' => 2,
            ],
        ],
    ],
    [
        'option' => 'index',
        'configuration' => [
            'number_of_replicas' => 2,
            'refresh_interval' => -1,
        ],
        'expected' => [
            'index' => [
                'number_of_replicas' => 2,
                'refresh_interval' => -1,
            ],
        ],
    ],
    [
        'option' => 'analysis',
        'configuration' => [
            'analyzer' => [
                'content' => [
                    'type' => 'custom',
                    'tokenizer' => 'whitespace',
                ],
            ],
        ],
        'expected' => [
            'analysis' => [
                'analyzer' => [
                    'content' => [
                        'type' => 'custom',
                        'tokenizer' => 'whitespace',
                    ],
                ],
            ],
        ],
    ],
]);

test('option setter', function (string $option, array $configuration, array $expected) {
    $actual = (new Settings)->$option($configuration);
    $this->assertSame($expected, $actual->toArray());
})->with('settings options');

test('exception is thrown when setter receives invalid number of arguments', function () {
    $this->expectException(BadMethodCallException::class);
    (new Settings)->index();
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
