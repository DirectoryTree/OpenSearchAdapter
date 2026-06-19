<?php

namespace DirectoryTree\OpenSearchAdapter\Exceptions;

use ErrorException;

/**
 * @see https://docs.opensearch.org/latest/api-reference/document-apis/bulk/
 */
class BulkRequestException extends ErrorException
{
    /**
     * Create a new bulk request exception from an OpenSearch response.
     *
     * @param  array<string, mixed>  $response
     */
    public static function fromResponse(array $response): self
    {
        return new self($response, self::makeMessageFromResponse($response));
    }

    /**
     * Create a new bulk request exception instance.
     *
     * @param  array<string, mixed>  $response
     */
    public function __construct(
        protected array $response,
        string $message = '',
    ) {
        parent::__construct($message);
    }

    /**
     * Get the exception context.
     *
     * @return array{response: array<string, mixed>}
     */
    public function context(): array
    {
        return [
            'response' => $this->getResponse(),
        ];
    }

    /**
     * Get the OpenSearch bulk response.
     *
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * Create the exception message from the OpenSearch bulk response.
     */
    protected static function makeMessageFromResponse(array $response): string
    {
        $items = $response['items'] ?? [];
        $count = count($items);

        $reason = sprintf('%s did not complete successfully.', $count > 0 ? $count.' bulk operation(s)' : 'One or more');

        $failedOperations = $items[0] ?? [];
        $firstOperation = reset($failedOperations);
        $firstError = ($firstOperation ?? [])['error'] ?? null;

        if (isset($firstError) && isset($firstError['type']) && isset($firstError['reason'])) {
            $reason .= sprintf(' %s: %s. Reason: %s.', $count > 1 ? 'First error' : 'Error', $firstError['type'], $firstError['reason']);
        }

        return sprintf('%s Catch the exception and use the %s::getResponse() method to get more details.', $reason, self::class);
    }
}
