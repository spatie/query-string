<?php

declare(strict_types=1);

namespace Spatie\QueryString\Tests\Ast;

use PHPUnit\Framework\TestCase;
use Spatie\QueryString\Ast\Ast;
use Spatie\QueryString\Ast\MultiNode;
use Spatie\QueryString\Ast\SingleNode;
use Spatie\QueryString\Ast\ToggleNode;

class AstTest extends TestCase
{
    /** @test */
    public function it_can_parse_a_string_into_nodes()
    {
        $ast = new Ast('toggleValue&singleValue=a&multiValue[]=b&multiValue[]=c');

        $this->assertInstanceOf(ToggleNode::class, $ast['toggleValue']);
        $this->assertInstanceOf(SingleNode::class, $ast['singleValue']);
        $this->assertInstanceOf(MultiNode::class, $ast['multiValue[]']);
    }

    /** @test */
    public function it_can_be_cast_to_string()
    {
        $ast = new Ast();

        $ast['toggleValue'] = new ToggleNode('toggleValue');
        $ast['singleValue'] = new SingleNode('singleValue', 'a');
        $ast['multiValue'] = new MultiNode('multiValue[]', ['b', 'c']);

        $string = (string) $ast;

        $this->assertEquals('multiValue[]=b&multiValue[]=c&singleValue=a&toggleValue', $string);
    }

    /** @test */
    public function to_string_sorts_the_paramters_alphabetically()
    {
        $ast = new Ast();

        $ast['c'] = new ToggleNode('c');
        $ast['a'] = new ToggleNode('a');
        $ast['b'] = new ToggleNode('b');

        $string = (string) $ast;

        $this->assertEquals('a&b&c', $string);
    }

    /** @test */
    public function a_toggle_node_can_be_added()
    {
        $ast = new Ast();

        $ast = $ast->add('toggleValue', null);

        $this->assertInstanceOf(ToggleNode::class, $ast['toggleValue']);
    }

    /** @test */
    public function a_toggle_node_can_be_removed()
    {
        $ast = new Ast();

        $ast = $ast->add('toggleValue', null);

        $ast = $ast->remove('toggleValue', null);

        $this->assertFalse(isset($ast['toggleValue']));
    }

    /** @test */
    public function a_single_node_can_be_added()
    {
        $ast = new Ast();

        $ast = $ast->add('singleValue', 'a');

        $this->assertInstanceOf(SingleNode::class, $ast['singleValue']);
    }

    /** @test */
    public function a_single_node_can_be_overwritten()
    {
        $ast = new Ast();

        $ast = $ast->add('singleValue', 'a');

        $ast = $ast->add('singleValue', 'b');

        $this->assertInstanceOf(SingleNode::class, $ast['singleValue']);

        /** @var SingleNode $node */
        $node = $ast['singleValue'];

        $this->assertEquals('b', $node->value());
    }

    /** @test */
    public function a_single_node_can_be_removed()
    {
        $ast = new Ast();

        $ast = $ast->add('singleValue', 'a');

        $ast = $ast->remove('singleValue', 'a');

        $this->assertFalse(isset($ast['singleValue']));
    }

    /** @test */
    public function a_multi_node_can_be_added()
    {
        $ast = new Ast();

        $ast = $ast->add('multiValue[]', 'a');
        $ast = $ast->add('multiValue[]', 'b');

        $this->assertInstanceOf(MultiNode::class, $ast['multiValue[]']);

        /** @var \Spatie\QueryString\Ast\MultiNode $node */
        $node = $ast['multiValue[]'];

        $this->assertTrue(isset($node->values()['a']));
        $this->assertTrue(isset($node->values()['b']));
    }

    /** @test */
    public function a_multi_node_can_be_removed()
    {
        $ast = new Ast();

        $ast = $ast->add('multiValue[]', 'a');
        $ast = $ast->add('multiValue[]', 'b');
        $ast = $ast->remove('multiValue[]', 'b');

        $this->assertInstanceOf(MultiNode::class, $ast['multiValue[]']);

        /** @var \Spatie\QueryString\Ast\MultiNode $node */
        $node = $ast['multiValue[]'];

        $this->assertTrue(isset($node->values()['a']));
        $this->assertFalse(isset($node->values()['b']));
    }
}
