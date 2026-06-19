<?php

namespace DirectoryTree\OpenSearchAdapter\Tests\Unit\Documents;

use DirectoryTree\OpenSearchAdapter\Documents\Routing;

test('routing values can be added and retrieved', function () {
    $routing = (new Routing)
        ->add('1', 'user1')
        ->add('2', 'user2');

    $this->assertTrue($routing->has('1'));
    $this->assertSame('user1', $routing->get('1'));
    $this->assertTrue($routing->has('2'));
    $this->assertSame('user2', $routing->get('2'));
    $this->assertFalse($routing->has('3'));
    $this->assertNull($routing->get('3'));
});
