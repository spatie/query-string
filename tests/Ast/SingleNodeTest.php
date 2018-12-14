<?php

declare(strict_types=1);

namespace Spatie\QueryString\Tests\Ast;

use PHPUnit\Framework\TestCase;
use Spatie\QueryString\Ast\SingleNode;

class SingleNodeTest extends TestCase
{
    /** @test */
    public function it_can_be_cast_to_string()
    {
        $node = new SingleNode('value', 'a');

        $this->assertEquals('value=a', (string) $node);
    }

    /** @test */
    public function active_when_the_same_value()
    {
        $node = new SingleNode('value', 'a');

        $this->assertTrue($node->isActive('a'));
        $this->assertFalse($node->isActive('b'));
    }

    /** @test */
    public function active_when_no_filterable_passed()
    {
        $node = new SingleNode('value', 'a');

        $this->assertTrue($node->isActive(null));
    }
}
