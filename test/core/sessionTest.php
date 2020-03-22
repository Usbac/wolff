<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Session;

class sessionTest extends TestCase
{

    const TEST_MSG = 'Hello world';


    public function setUp(): void
    {
        Session::set('msg', self::TEST_MSG);
        Session::setVarTime('msg', 10);
        Session::addVarTime('msg', 10);
    }


    public function testInit()
    {
        $this->assertTrue(Session::has('msg'));
        /* 2 because of the msg variable declared and the variable
           keeping track of the variables time. */
        $this->assertEquals(2, Session::count());
        $this->assertEquals($_SESSION['msg'], Session::get('msg'));
        $this->assertEquals(self::TEST_MSG, Session::get('msg'));
        $this->assertEquals((10 + 10) * 60, Session::getVarTime('msg'));

        Session::unset('msg');
        $this->assertEquals(null, Session::get('msg'));
    }

}
