# OpenSearch Adapter

A PHP adapter for the official [OpenSearch PHP client](https://github.com/opensearch-project/opensearch-php).

## Installation

Install the package with Composer:

```bash
composer require directorytree/opensearch-adapter
```

## Usage

Create the adapter managers with an `OpenSearch\Client` instance:

```php
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManager;
use DirectoryTree\OpenSearchAdapter\Indices\IndexManager;
use OpenSearch\Client;

$documents = new DocumentManager($client);
$indices = new IndexManager($client);
```

The adapter provides value objects for documents, index mappings, settings, aliases, search requests, and search responses.
