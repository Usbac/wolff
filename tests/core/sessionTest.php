<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Session;

class SessionTest extends TestCase
{

    public function setUp(): void
    {
        Session::set('msg', 'Hello world');
        Session::setVarTime('msg', 10);
        Session::addVarTime('msg', 10);
    }


    public function testInit()
    {
        $this->assertFalse(Session::expired());
        $this->assertTrue(Session::has('msg'));
        $this->assertFalse(Session::has('another_msg'));
        $this->assertEquals($_SESSION['msg'], Session::get('msg'));
        $this->assertEquals('Hello world', Session::get('msg'));
        $this->assertEquals((10 + 10) * 60, Session::getVarTime('msg'));
        $this->assertEquals('20:00', Session::getVarTime('msg', 'i:s'));
        $this->assertEquals($_SESSION, Session::get());
        Session::setTime(10);
        Session::addTime(20);
        $this->assertEquals(30 * 60, Session::getRemainingTime());
        $this->assertEquals('00:30', Session::getRemainingTime('H:i'));

        Session::unset('msg');
        $this->assertNull(Session::get('msg'));
    }
}
