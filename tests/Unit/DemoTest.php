<?php

namespace App\Tests\Unit;

use App\Demo;
use Monolog\Test\TestCase;

class DemoTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertEquals('Hello World', new Demo()->returnSomething());
    }
}