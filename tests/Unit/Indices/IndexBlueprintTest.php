<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Indices;

use DirectoryTree\OpenSearchAdapter\Indices\IndexBlueprint;
use DirectoryTree\OpenSearchAdapter\Indices\Mapping;
use DirectoryTree\OpenSearchAdapter\Indices\Settings;

test('index default values', function () {
    $index = new IndexBlueprint('foo');

    $this->assertNull($index->settings());
    $this->assertNull($index->mapping());
});

test('index getters', function () {
    $mapping = new Mapping;
    $settings = new Settings;
    $index = new IndexBlueprint('foo', $mapping, $settings);

    $this->assertSame('foo', $index->name());
    $this->assertSame($mapping, $index->mapping());
    $this->assertSame($settings, $index->settings());
});
