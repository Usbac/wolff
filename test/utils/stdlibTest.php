<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class StdlibTest extends TestCase
{

    const JSON = ' {
        "name": "John",
        "age": 21,
        "city": "New York"
    }';


    public function setUp(): void
    {
        require dirname(__DIR__, 2) . '/vendor/usbac/wolff-framework/src/stdlib.php';
    }


    public function testInit()
    {
        $arr = [
            'user' => [
                'name' => 'Margaret',
                'age'  => 63
            ]
        ];

        $assoc_arr = [
            'name'    => 'Evan You',
            'country' => 'China'
        ];

        $non_assoc_arr = [ 'Evan You', 'China' ];

        $example_arr = [
            'mauretania', 'lusitania', 'queen_mary'
        ];

        $expected_arr = [
            0 => 'mauretania', 2 => 'queen_mary'
        ];

        $this->assertTrue(arrayRemove($example_arr, 'lusitania'));
        $this->assertEquals($expected_arr, $example_arr);
        $this->assertEquals('527KB', bytesToString('540000'));
        $this->assertEquals('9.537MB', bytesToString('10000000', 3));
        $this->assertTrue(isJson(self::JSON));
        $this->assertEquals($arr['user']['name'], val($arr, 'user.name'));
        $this->assertEquals('', getClientIP());
        $this->assertEquals(5.24, average([ 2.5, 5.46, 4, 9 ]));
        $this->assertTrue(isInt('1'));
        $this->assertTrue(isFloat('1.5'));
        $this->assertTrue(isAssoc($assoc_arr));
        $this->assertFalse(isAssoc($non_assoc_arr));
    }
}
