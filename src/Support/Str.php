<?php

namespace DirectoryTree\OpenSearchAdapter\Support;

class Str
{
    /**
     * Convert a camel-case value into snake case.
     */
    public static function snake(string $value): string
    {
        return strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }
}
