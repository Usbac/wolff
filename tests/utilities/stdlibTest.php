<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class stdlibTest extends TestCase
{

    const JSON = ' {
        "name": "John",
        "age": 21,
        "city": "New York"
    }';


    public function testInit()
    {
        $arr = [
            'user' => [
                'name' => 'Margaret',
                'age'  => 63
            ]
        ];

        $this->assertTrue(isJson(self::JSON));
        $this->assertEquals(CONFIG['db_password'], config('db_password'));
        $this->assertEquals($arr['user']['name'], val($arr, 'user.name'));
        $this->assertEquals('', getClientIP());
        $this->assertEquals(CORE_CONFIG['version'], wolffVersion());
        $this->assertEquals(5.24, average([ 2.5, 5.46, 4, 9 ]));
        $this->assertTrue(isInt('1'));
        $this->assertTrue(isFloat('1.5'));
        $this->assertTrue(isBool('1'));
        $this->assertTrue(local());
        $this->assertTrue(inCli());
    }

}
