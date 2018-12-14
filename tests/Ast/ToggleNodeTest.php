<?php

declare(strict_types=1);

namespace Spatie\QueryString\Tests\Ast;

use PHPUnit\Framework\TestCase;
use Spatie\QueryString\Ast\ToggleNode;

class ToggleNodeTest extends TestCase
{
    /** @test */
    public function it_can_be_cast_to_string()
    {
        $node = new ToggleNode('value');

        $this->assertEquals('value', (string) $node);
    }

    /** @test */
    public function toggle_node_is_always_active()
    {
        $node = new ToggleNode('value');

        $this->assertTrue($node->isActive(null));
    }
}
