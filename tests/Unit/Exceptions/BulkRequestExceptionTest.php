<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Exceptions;

use DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException;

test('response can be retrieved', function () {
    $response = [
        'took' => 486,
        'errors' => true,
        'items' => [
            [
                'update' => [
                    '_index' => 'index1',
                    '_type' => '_doc',
                    '_id' => '5',
                    'status' => 404,
                    'error' => [
                        'type' => 'document_missing_exception',
                        'reason' => '[_doc][5]: document missing',
                        'index_uuid' => 'aAsFqTI0Tc2W0LCWgPNrOA',
                        'shard' => '0',
                        'index' => 'index1',
                    ],
                ],
            ],
        ],
    ];

    $exception = new BulkRequestException($response);

    $this->assertSame($response, $exception->getResponse());
});

test('first error message from response is given in exception message', function () {
    $response = [
        'took' => 486,
        'errors' => true,
        'items' => [
            [
                'update' => [
                    '_index' => 'index1',
                    '_type' => '_doc',
                    '_id' => '5',
                    'status' => 404,
                    'error' => [
                        'type' => 'document_missing_exception',
                        'reason' => '[_doc][5]: document missing',
                        'index_uuid' => 'aAsFqTI0Tc2W0LCWgPNrOA',
                        'shard' => '0',
                        'index' => 'index1',
                    ],
                ],
            ],
        ],
    ];

    $exception = new BulkRequestException($response);

    $this->assertEquals(
        '1 bulk operation(s) did not complete successfully. Error: document_missing_exception. Reason: [_doc][5]: document missing. Catch the exception and use the DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException::getResponse() method to get more details.',
        $exception->getMessage()
    );
});

test('exception can be thrown with many errors in response', function () {
    $response = [
        'took' => 486,
        'errors' => true,
        'items' => [
            [
                'update' => [
                    '_index' => 'index1',
                    '_type' => '_doc',
                    '_id' => '5',
                    'status' => 404,
                    'error' => [
                        'type' => 'document_missing_exception',
                        'reason' => '[_doc][5]: document missing',
                        'index_uuid' => 'aAsFqTI0Tc2W0LCWgPNrOA',
                        'shard' => '0',
                        'index' => 'index1',
                    ],
                ],
            ],
            [
                'index' => [
                    '_index' => 'index1',
                    '_type' => '_doc',
                    '_id' => '5',
                    'status' => 404,
                    'error' => [
                        'type' => 'mapper_parsing_exception',
                        'reason' => 'failed to parse field',
                        'index_uuid' => 'aAsFqTI0Tc2W0LCWgPNrOA',
                        'shard' => '0',
                        'index' => 'index1',
                    ],
                ],
            ],
        ],
    ];

    $exception = new BulkRequestException($response);

    $this->assertEquals(
        '2 bulk operation(s) did not complete successfully. First error: document_missing_exception. Reason: [_doc][5]: document missing. Catch the exception and use the DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException::getResponse() method to get more details.',
        $exception->getMessage()
    );
});

test('exception can be thrown with missing error in response', function () {
    $response = [
        'took' => 486,
        'errors' => true,
        'items' => [
            [
                'update' => [
                    '_index' => 'index1',
                    '_type' => '_doc',
                    '_id' => '5',
                    'status' => 404,
                ],
            ],
        ],
    ];

    $exception = new BulkRequestException($response);

    $this->assertEquals(
        '1 bulk operation(s) did not complete successfully. Catch the exception and use the DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException::getResponse() method to get more details.',
        $exception->getMessage()
    );
});

test('exception can be thrown with missing items in response', function () {
    $response = [
        'took' => 486,
        'errors' => true,
        'items' => [],
    ];

    $exception = new BulkRequestException($response);

    $this->assertEquals(
        'One or more did not complete successfully. Catch the exception and use the DirectoryTree\OpenSearchAdapter\Exceptions\BulkRequestException::getResponse() method to get more details.',
        $exception->getMessage()
    );
});
