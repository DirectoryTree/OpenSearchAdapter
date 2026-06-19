<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use BadMethodCallException;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    public static function optionsProvider(): array
    {
        return [
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
        ];
    }

    #[DataProvider('optionsProvider')]
    #[TestDox('Test $option option setter')]
    public function test_option_setter(string $option, array $configuration, array $expected): void
    {
        $actual = (new Settings)->$option($configuration);
        $this->assertSame($expected, $actual->toArray());
    }

    public function test_exception_is_thrown_when_setter_receives_invalid_number_of_arguments(): void
    {
        $this->expectException(BadMethodCallException::class);
        (new Settings)->index();
    }

    public function test_default_array_casting(): void
    {
        $this->assertSame([], (new Settings)->toArray());
    }

    public function test_configured_array_casting(): void
    {
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
    }
}
