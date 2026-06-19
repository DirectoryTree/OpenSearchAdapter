<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Search;

use DirectoryTree\OpenSearchAdapter\Search\Bucket;
use PHPUnit\Framework\TestCase;

class BucketTest extends TestCase
{
    /**
     * @var Bucket
     */
    protected $bucket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bucket = new Bucket([
            'key' => 'electronic',
            'doc_count' => 6,
        ]);
    }

    public function test_key_can_be_retrieved(): void
    {
        $this->assertSame('electronic', $this->bucket->key());
    }

    public function test_doc_count_can_be_retrieved(): void
    {
        $this->assertSame(6, $this->bucket->docCount());
    }

    public function test_raw_representation_can_be_retrieved(): void
    {
        $this->assertSame([
            'key' => 'electronic',
            'doc_count' => 6,
        ], $this->bucket->raw());
    }
}
