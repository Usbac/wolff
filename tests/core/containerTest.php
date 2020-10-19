<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Container;

class ContainerTest extends TestCase
{

    public function setUp(): void
    {
        Container::add('exampleClass', function ($param1, $param2) {
            return new exampleClass($param1, $param2);
        });

        Container::add('exampleClass2', Container::class);

        Container::singleton('singleton', function ($param1, $param2) {
            return new exampleClass($param1, $param2);
        });

        Container::singleton('singleton_2', 'Tests\ContainerTest');

        Container::singleton(ContainerTest::class);
    }


    public function testInit()
    {
        $instance = Container::get('exampleClass', [ 'Hello', 'world' ]);

        $this->assertTrue(Container::has('exampleClass'));
        $this->assertFalse(Container::has('anotherClass'));
        $this->assertInstanceOf(exampleClass::class, $instance);
        $this->assertInstanceOf(Container::class, Container::get('exampleClass2'));
        $this->assertEquals('Hello', $instance->getParam1());
        $this->assertEquals('world', $instance->getParam2());
    }


    public function testSingleton()
    {
        $singleton_1 = Container::get('singleton', [ 'Hello', 'world' ]);
        $singleton_2 = Container::get('singleton', [ 'Hello', 'world' ]);
        $singleton_1->setParam1('wolff');

        $this->assertInstanceOf(exampleClass::class, $singleton_1);
        $this->assertEquals('wolff', $singleton_2->getParam1());
        $this->assertInstanceOf(ContainerTest::class, Container::get('singleton_2'));
        $this->assertNotNull(Container::get(ContainerTest::class));
    }
}
