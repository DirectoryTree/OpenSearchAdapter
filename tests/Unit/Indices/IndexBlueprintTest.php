<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;
use PHPUnit\Framework\TestCase;

class IndexBlueprintTest extends TestCase
{
    public function test_index_default_values(): void
    {
        $index = new IndexBlueprint('foo');

        $this->assertNull($index->settings());
        $this->assertNull($index->mapping());
    }

    public function test_index_getters(): void
    {
        $mapping = new Mapping;
        $settings = new Settings;
        $index = new IndexBlueprint('foo', $mapping, $settings);

        $this->assertSame('foo', $index->name());
        $this->assertSame($mapping, $index->mapping());
        $this->assertSame($settings, $index->settings());
    }
}
