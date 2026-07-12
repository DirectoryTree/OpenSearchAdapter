<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Documents;

use DirectoryTree\OpenSearchAdapter\Documents\DocumentRouting;

it('adds and retrieves routing values', function () {
    $routing = DocumentRouting::make('1', 'user1')
        ->add('2', 'user2');

    $this->assertTrue($routing->has('1'));
    $this->assertSame('user1', $routing->get('1'));
    $this->assertTrue($routing->has('2'));
    $this->assertSame('user2', $routing->get('2'));
    $this->assertFalse($routing->has('3'));
    $this->assertNull($routing->get('3'));
    $this->assertSame([
        '1' => 'user1',
        '2' => 'user2',
    ], $routing->toArray());
});
