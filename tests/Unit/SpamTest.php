<?php

namespace Tests\Feature;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    protected $spam;

    public function setUp()
    {
        parent::setUp();

        $this->spam = new Spam();
    }

    /** @test */
    public function it_checks_invalid_keywords()
    {
        $this->assertFalse($this->spam->detect('td'));

        $this->expectException(\Exception::class);

        $this->spam->detect('tmd');
    }

    /** @test */
    public function it_checks_for_key_held_down()
    {
        $this->expectException(\Exception::class);

        $this->spam->detect('Hello worlddddddddd');
    }
}
