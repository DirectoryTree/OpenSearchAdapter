# OpenSearch Adapter

A PHP adapter for the [OpenSearch PHP client](https://github.com/opensearch-project/opensearch-php).

## Installation

Install the package with Composer:

```bash
composer require directorytree/opensearch-adapter
```

## Creating Managers

Create the adapter managers from an `OpenSearch\Client` instance:

```php
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManager;
use DirectoryTree\OpenSearchAdapter\Indices\IndexManager;
use OpenSearch\Client;

$documents = new DocumentManager($client);
$indices = new IndexManager($client);
```

## Managing Indices

Use an index blueprint when you want fluent mapping and settings builders:

```php
use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;

$mapping = (new Mapping)
    ->keyword('id')
    ->text('title')
    ->object('author', [
        'properties' => [
            'name' => ['type' => 'text'],
        ],
    ]);

$settings = (new Settings)->index([
    'number_of_shards' => 1,
    'number_of_replicas' => 0,
    'refresh_interval' => '1s',
]);

$indices->create(new IndexBlueprint('books', $mapping, $settings));
```

Use `field()` for less common, custom, or newer OpenSearch field types:

```php
$mapping = (new Mapping)->field('embedding', 'custom_vector', [
    'dimension' => 1536,
]);
```

Settings include top-level setters for common OpenSearch settings groups:

```php
$settings = (new Settings)
    ->index([
        'number_of_shards' => 1,
        'number_of_replicas' => 0,
    ])
    ->analysis([
        'analyzer' => [
            'content' => [
                'type' => 'custom',
                'tokenizer' => 'whitespace',
            ],
        ],
    ])
    ->similarity([
        'default' => [
            'type' => 'BM25',
        ],
    ]);
```

Use `set()` for top-level settings groups that do not have a dedicated method:

```php
$settings = (new Settings)
    ->set('custom_group', [
        'enabled' => true,
    ]);
```

## Indexing Documents

Documents contain the OpenSearch document ID and source payload:

```php
use DirectoryTree\OpenSearchAdapter\Documents\Document;

$documents->index('books', [
    new Document('1', [
        'title' => 'The Hobbit',
    ]),
]);
```

Document routing values can be attached by document ID:

```php
use DirectoryTree\OpenSearchAdapter\Documents\DocumentRouting;

$routing = DocumentRouting::make('1', 'tenant-1');

$documents->index('books', [
    new Document('1', [
        'title' => 'The Hobbit',
    ]),
], routing: $routing);
```

## Searching Documents

Build a search request with raw OpenSearch query fragments:

```php
use DirectoryTree\OpenSearchAdapter\Search\SearchRequest;

$request = (new SearchRequest([
    'match' => [
        'title' => 'hobbit',
    ],
]))
    ->size(10)
    ->highlight([
        'fields' => [
            'title' => new stdClass,
        ],
    ]);

$response = $documents->search('books', $request);

$total = $response->total();

$hits = array_map(
    fn ($hit) => $hit->document()->source(),
    $response->hits(),
);
```

## Aliases

Aliases can include optional filters and routing values:

```php
use DirectoryTree\OpenSearchAdapter\Indices\Alias;

$indices->putAlias('books', new Alias(
    'tenant-books',
    filter: [
        'term' => [
            'tenant_id' => 1,
        ],
    ],
    routing: 'tenant-1',
));

$aliases = $indices->getAliases('books');
```

## Raw Responses

Search response objects expose the original OpenSearch payload through `raw()`:

```php
$rawHit = $response->hits()[0]->raw();
$rawResponse = $response->raw();
```
