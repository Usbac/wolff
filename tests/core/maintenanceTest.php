<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Maintenance;

class MaintenanceTest extends TestCase
{

    public function setUp(): void
    {
        $_SERVER['HTTP_CLIENT_IP'] = '192.168.1.2';

        Maintenance::setStatus(true);
        Maintenance::setIPs([
            '192.168.1.2',
            '::1',
        ]);
        Maintenance::set(function($req, $res) {
            $req->foo = 'bar';
            $res->foo = 'bar';
        });
    }


    public function testInit()
    {
        $this->assertContains('192.168.1.2', Maintenance::getIPs());
        $this->assertContains('::1', Maintenance::getIPs());
        $this->assertNotContains('192.168.1.3', Maintenance::getIPs());
        $this->assertTrue(Maintenance::hasAccess());
        Maintenance::removeIP('192.168.1.2');
        $this->assertFalse(Maintenance::hasAccess());
        $this->assertNotContains('192.168.1.2', Maintenance::getIPs());
        $this->assertTrue(Maintenance::isEnabled());
        Maintenance::setStatus(false);
        $this->assertFalse(Maintenance::isEnabled());

        $req = new \Wolff\Core\Http\Request([], [], [], [], []);
        $res = new \Wolff\Core\Http\Response;
        Maintenance::call($req, $res);
        $this->assertEquals('bar', $req->foo);
        $this->assertEquals('bar', $res->foo);
    }


    public function tearDown(): void
    {
        unset($_SERVER['HTTP_CLIENT_IP']);
    }
}
