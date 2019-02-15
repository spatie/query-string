<?php

declare(strict_types=1);

namespace Spatie\QueryString\Tests\Ast;

use PHPUnit\Framework\TestCase;
use Spatie\QueryString\Ast\MultiNode;

class MultiNodeTest extends TestCase
{
    /** @test */
    public function two_nodes_can_be_merged()
    {
        $nodeA = new MultiNode('value[]', ['a']);
        $nodeB = new MultiNode('value[]', ['b']);

        $mergedNode = $nodeA->merge($nodeB);

        $this->assertEquals(['a' => 'a', 'b' => 'b'], $mergedNode->values());
    }

    /** @test */
    public function it_can_be_cast_to_string()
    {
        $node = new MultiNode('value[]', ['a', 'b']);

        $this->assertEquals('value[]=a&value[]=b', (string) $node);
    }

    /** @test */
    public function active_when_value_isset()
    {
        $node = new MultiNode('value', ['a', 'b']);

        $this->assertTrue($node->isActive('a'));
        $this->assertTrue($node->isActive('b'));
        $this->assertFalse($node->isActive('c'));
    }

    /** @test */
    public function active_when_no_filterable_passed()
    {
        $node = new MultiNode('value', ['a', 'b']);

        $this->assertTrue($node->isActive(null));
    }

    /** @test */
    public function it_can_remove_a_value()
    {
        $node = new MultiNode('value', ['a', 'b']);

        $node = $node->remove('a');

        $this->assertEquals(['b' => 'b'], $node->values());
    }
}
