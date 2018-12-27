<?php

declare(strict_types=1);

namespace Spatie\QueryString\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\QueryString\StringHelper;

class StringHelperTest extends TestCase
{
    /** @test */
    public function it_can_check_string_starting_with_needle()
    {
        $this->assertFalse(StringHelper::startsWith('12345678', '0'));
        $this->assertTrue(StringHelper::startsWith('12345678', '1'));
    }

    /** @test */
    public function it_can_check_string_ending_with_needle()
    {
        $this->assertFalse(StringHelper::endsWith('12345678', '0'));
        $this->assertTrue(StringHelper::endsWith('12345678[]', '[]'));
    }

    /** @test */
    public function it_can_ensure_string_ending_with_needle()
    {
        $this->assertEquals('subject_needle', StringHelper::ensureEndsWith('subject_', 'needle'));
        $this->assertEquals('subject_needle', StringHelper::ensureEndsWith('subject_needle', 'needle'));
    }

    /** @test */
    public function it_can_replace_last_subject_with_specific_string()
    {
        $this->assertEquals('subject', StringHelper::replaceLast('[]', 'replace', 'subject'));
        $this->assertEquals('subject_replace', StringHelper::replaceLast('[]', '_replace', 'subject[]'));
    }
}
