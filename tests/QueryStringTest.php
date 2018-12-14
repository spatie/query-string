<?php

declare(strict_types=1);

namespace Spatie\QueryString\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\QueryString\QueryString;

class QueryStringTest extends TestCase
{
    /** @test */
    public function it_can_toggle_a_filter_without_value()
    {
        $this->assertEquals('/?value', QueryString::new('/')->filter('value'));

        $this->assertEquals('/?', QueryString::new('/?value')->filter('value'));
    }

    /** @test */
    public function it_can_clear_a_filter_without_value()
    {
        $this->assertEquals('/?', QueryString::new('/?value')->clear('value'));
    }

    /** @test */
    public function active_for_filter_without_value()
    {
        $this->assertTrue(QueryString::new('/?value')->isActive('value'));
        $this->assertFalse(QueryString::new('/?')->isActive('value'));
    }

    /** @test */
    public function it_can_toggle_a_single_value()
    {
        $this->assertEquals('/?value=a', QueryString::new('/')->filter('value', 'a'));

        $this->assertEquals('/?', QueryString::new('/?value=a')->filter('value', 'a'));

        $this->assertEquals('/?value=b', QueryString::new('/?value=a')->filter('value', 'b'));
    }

    /** @test */
    public function it_can_clear_a_single_filter()
    {
        $this->assertEquals('/?', QueryString::new('/?value=a')->clear('value'));
    }

    /** @test */
    public function active_for_single_filter()
    {
        $this->assertTrue(QueryString::new('/?value=a')->isActive('value', 'a'));
        $this->assertFalse(QueryString::new('/?value=a')->isActive('value', 'b'));
        $this->assertFalse(QueryString::new('/?')->isActive('value', 'a'));
    }

    /** @test */
    public function it_can_toggle_a_multi_filter()
    {
        $this->assertEquals('/?', QueryString::new('/?value[]=a')->filter('value[]', 'a'));
        $this->assertEquals('/?value[]=a&value[]=b', QueryString::new('/?value[]=a')->filter('value[]', 'b'));
        $this->assertEquals('/?value[]=b', QueryString::new('/?value[]=a&value[]=b')->filter('value[]', 'a'));
        $this->assertEquals('/?value[]=a', QueryString::new('/')->filter('value[]', 'a'));
    }

    /** @test */
    public function it_can_clear_a_multi_filter()
    {
        $this->assertEquals('/?', QueryString::new('/?value[]=a&value[]=b')->clear('value[]'));
    }

    /** @test */
    public function active_for_multi_filter()
    {
        $this->assertTrue(QueryString::new('/?value[]=a')->isActive('value[]', 'a'));
        $this->assertTrue(QueryString::new('/?value[]=a')->isActive('value[]'));
        $this->assertFalse(QueryString::new('/?value[]=a')->isActive('value[]', 'b'));
        $this->assertFalse(QueryString::new('/?')->isActive('value[]', 'a'));
    }
}
