<?php

use DirectoryTree\OpenSearchAdapter\Support\Str;

it('converts camel-case values to snake case', function (string $value, string $expected): void {
    expect(Str::snake($value))->toBe($expected);
})->with([
    ['geoPoint', 'geo_point'],
    ['searchAsYouType', 'search_as_you_type'],
    ['analysis', 'analysis'],
]);
