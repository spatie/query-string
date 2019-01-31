<?php

declare(strict_types=1);

namespace Spatie\QueryString\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\QueryString\QueryString;

class QueryStringTest extends TestCase
{
    /** @test */
    public function it_can_toggle_a_toggle_without_value()
    {
        $this->assertEquals('/?value', QueryString::new('/')->toggle('value'));

        $this->assertEquals('/?', QueryString::new('/?value')->toggle('value'));
    }

    /** @test */
    public function it_can_clear_a_toggle_without_value()
    {
        $this->assertEquals('/?', QueryString::new('/?value')->clear('value'));
    }

    /** @test */
    public function active_for_toggle_without_value()
    {
        $this->assertTrue(QueryString::new('/?value')->isActive('value'));
        $this->assertFalse(QueryString::new('/?')->isActive('value'));
    }

    /** @test */
    public function it_can_toggle_a_single_value()
    {
        $this->assertEquals('/?value=a', QueryString::new('/')->toggle('value', 'a'));

        $this->assertEquals('/?', QueryString::new('/?value=a')->toggle('value', 'a'));

        $this->assertEquals('/?value=b', QueryString::new('/?value=a')->toggle('value', 'b'));
    }

    /** @test */
    public function it_can_clear_a_single_toggle()
    {
        $this->assertEquals('/?', QueryString::new('/?value=a')->clear('value'));
    }

    /** @test */
    public function active_for_single_toggle()
    {
        $this->assertTrue(QueryString::new('/?value=a')->isActive('value', 'a'));
        $this->assertFalse(QueryString::new('/?value=a')->isActive('value', 'b'));
        $this->assertFalse(QueryString::new('/?')->isActive('value', 'a'));
    }

    /** @test */
    public function it_can_toggle_a_multi_toggle()
    {
        $this->assertEquals('/?', QueryString::new('/?value[]=a')->toggle('value[]', 'a'));
        $this->assertEquals('/?value[]=a&value[]=b', QueryString::new('/?value[]=a')->toggle('value[]', 'b'));
        $this->assertEquals('/?value[]=b', QueryString::new('/?value[]=a&value[]=b')->toggle('value[]', 'a'));
        $this->assertEquals('/?value[]=a', QueryString::new('/')->toggle('value[]', 'a'));
    }

    /** @test */
    public function it_can_clear_a_multi_toggle()
    {
        $this->assertEquals('/?', QueryString::new('/?value[]=a&value[]=b')->clear('value[]'));
    }

    /** @test */
    public function active_for_multi_toggle()
    {
        $this->assertTrue(QueryString::new('/?value[]=a')->isActive('value[]', 'a'));
        $this->assertTrue(QueryString::new('/?value[]=a')->isActive('value[]'));
        $this->assertFalse(QueryString::new('/?value[]=a')->isActive('value[]', 'b'));
        $this->assertFalse(QueryString::new('/?')->isActive('value[]', 'a'));
    }

    /** @test */
    public function a_filter_without_value_can_be_toggled()
    {
        $queryString = new QueryString('/');

        $queryString = $queryString->filter('value');

        $this->assertEquals('/?filter[value]', (string) $queryString);
    }

    /** @test */
    public function a_filter_with_single_value_can_be_toggled()
    {
        $queryString = new QueryString('/');

        $queryString = $queryString->filter('value', 'a');

        $this->assertEquals('/?filter[value]=a', (string) $queryString);
    }

    /** @test */
    public function a_filter_with_multiple_values_can_be_toggled()
    {
        $queryString = new QueryString('/');

        $queryString = $queryString->filter('value[]', 'a');

        $this->assertEquals('/?filter[value][]=a', (string) $queryString);
    }

    /** @test */
    public function sort_can_be_toggled()
    {
        $queryString = new QueryString('/');

        $queryString = $queryString->sort('id');

        $this->assertEquals('/?sort=id', (string) $queryString);

        $queryString = $queryString->sort('id');

        $this->assertEquals('/?sort=-id', (string) $queryString);

        $queryString = $queryString->sort('id');

        $this->assertEquals('/?sort=id', (string) $queryString);
    }

    /** @test */
    public function sort_can_be_toggled_in_reverse()
    {
        $queryString = new QueryString('/');

        $queryString = $queryString->sort('-id');

        $this->assertEquals('/?sort=-id', (string) $queryString);

        $queryString = $queryString->sort('-id');

        $this->assertEquals('/?sort=id', (string) $queryString);

        $queryString = $queryString->sort('-id');

        $this->assertEquals('/?sort=-id', (string) $queryString);
    }

    /** @test */
    public function it_can_set_with_base_url()
    {
        $queryString = new QueryString('/base/url');

        $queryString = $queryString->withBaseUrl('/base/urls');

        $this->assertInstanceOf(QueryString::class, $queryString);
        $this->assertEquals('/base/urls?', (string) $queryString);
    }

    public function a_single_toggle_default_value_is_ignored()
    {
        $queryString = new QueryString('/');

        $queryString = $queryString->default('value', 'a');

        $this->assertEquals('/?', (string) $queryString->toggle('value', 'a'));
        $this->assertEquals('/?value=b', (string) $queryString->toggle('value', 'b'));
    }

    /** @test */
    public function a_multi_toggle_default_value_is_ignored()
    {
        $queryString = new QueryString('/');

        $queryString = $queryString->default('value[]', 'a');

        $this->assertEquals('/?', (string) $queryString->toggle('value[]', 'a'));
        $this->assertEquals('/?value[]=b', (string) $queryString->toggle('value[]', 'b'));
    }

    /** @test */
    public function a_default_value_is_removed_when_switched_to()
    {
        $queryString = new QueryString('/?value=b');

        $queryString = $queryString->default('value', 'a');

        $this->assertEquals('/?', (string) $queryString->toggle('value', 'a'));
    }

    /** @test */
    public function it_can_set_a_page()
    {
        $queryString = new QueryString('/');

        $this->assertEquals('/?page=2', (string) $queryString->page(2));
    }

    /** @test */
    public function it_can_return_the_current_page()
    {
        $this->assertEquals(2, (new QueryString('/?page=2'))->getCurrentPage());
        $this->assertEquals(1, (new QueryString('/'))->getCurrentPage());
    }

    /** @test */
    public function it_can_set_the_next_page()
    {
        $this->assertEquals('/?page=2', (string) (new QueryString('/?page=1'))->nextPage());
    }

    /** @test */
    public function it_can_set_the_previous_page()
    {
        $this->assertEquals('/?page=1', (string) (new QueryString('/?page=2'))->previousPage());
        $this->assertEquals('/?page=1', (string) (new QueryString('/?page=1'))->previousPage());
    }

    /** @test */
    public function is_current_page()
    {
        $this->assertTrue((new QueryString('/?page=2'))->isCurrentPage(2));
        $this->assertFalse((new QueryString('/?page=2'))->isCurrentPage(1));
    }

    /** @test */
    public function default_combined_with_page()
    {
        $queryString = (new QueryString('/?page=2'))->default('page', 1);

        $this->assertEquals('/?', (string) $queryString->previousPage());
        $this->assertEquals('/?page=3', (string) $queryString->nextPage());
    }

    /** @test */
    public function reset_page()
    {
        $queryString = (new QueryString('/?page=2'));

        $this->assertEquals('/?', (string) $queryString->resetPage());
    }

    /** @test */
    public function page_is_reset_when_any_other_value_is_enabled()
    {
        $queryString = (new QueryString('/?page=2'));

        $this->assertEquals('/?value', (string) $queryString->enable('value'));
    }

    /** @test */
    public function page_is_reset_when_any_other_value_is_disabled()
    {
        $queryString = (new QueryString('/?page=2&value'));

        $this->assertEquals('/?', (string) $queryString->disable('value'));
    }
}
