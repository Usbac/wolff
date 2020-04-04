<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Container;

class ContainerTest extends TestCase
{

    const PARAM_1 = 'Hello';
    const PARAM_2 = 'world';
    const UNIQUE_PARAM = 'wolff';


    public function setUp(): void
    {
        Container::add('exampleClass', function ($param1, $param2) {
            return new exampleClass($param1, $param2);
        });

        Container::singleton('singleton', function ($param1, $param2) {
            return new exampleClass($param1, $param2);
        });
    }


    public function testInit()
    {
        $instance = Container::get('exampleClass', [ self::PARAM_1, self::PARAM_2 ]);

        $this->assertTrue(Container::has('exampleClass'));
        $this->assertFalse(Container::has('anotherClass'));
        $this->assertInstanceOf(exampleClass::class, $instance);
        $this->assertEquals(self::PARAM_1, $instance->getParam1());
        $this->assertEquals(self::PARAM_2, $instance->getParam2());
    }


    public function testSingleton()
    {
        $singleton_1 = Container::get('singleton', [ self::PARAM_1, self::PARAM_2 ]);
        $singleton_2 = Container::get('singleton', [ self::PARAM_1, self::PARAM_2 ]);
        $singleton_1->setParam1(self::UNIQUE_PARAM);

        $this->assertInstanceOf(exampleClass::class, $singleton_1);
        $this->assertEquals(self::UNIQUE_PARAM, $singleton_2->getParam1());
    }
}
