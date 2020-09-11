<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Maintenance;
use Wolff\Exception\FileNotReadableException;

class MaintenanceTest extends TestCase
{

    const FILE = '../system/testing_maintenance_whitelist.txt';


    public function setUp(): void
    {
        $_SERVER['HTTP_CLIENT_IP'] = '192.168.1.2';

        Maintenance::setStatus(true);
        Maintenance::setFile('system/testing_maintenance_whitelist.txt');
        Maintenance::addAllowedIP('192.168.1.2');
        Maintenance::addAllowedIP('::1');
        Maintenance::set(function($req, $res) {
            $req->foo = 'bar';
            $res->foo = 'bar';
        });
    }


    public function testInit()
    {
        $this->assertFileExists(self::FILE);
        $this->assertContains('192.168.1.2', Maintenance::getAllowedIPs());
        $this->assertContains('::1', Maintenance::getAllowedIPs());
        $this->assertNotContains('192.168.1.3', Maintenance::getAllowedIPs());
        $this->assertTrue(Maintenance::hasAccess());
        $this->assertTrue(Maintenance::removeAllowedIP('192.168.1.2'));
        $this->assertFalse(Maintenance::hasAccess());
        $this->assertNotContains('192.168.1.2', Maintenance::getAllowedIPs());
        $this->assertTrue(Maintenance::isEnabled());
        Maintenance::setStatus(false);
        $this->assertFalse(Maintenance::isEnabled());
        Maintenance::setFile('non_existent_file');
        $this->assertFalse(Maintenance::hasAccess());
        $this->assertFalse(Maintenance::getAllowedIPs());
        $this->assertFalse(Maintenance::addAllowedIP('invalid ip'));
        $this->assertFalse(Maintenance::removeAllowedIP('invalid ip'));

        $req = new \Wolff\Core\Http\Request([], [], [], [], []);
        $res = new \Wolff\Core\Http\Response;
        Maintenance::call($req, $res);
        $this->assertEquals('bar', $req->foo);
        $this->assertEquals('bar', $res->foo);
        $this->expectException(FileNotReadableException::class);
        Maintenance::removeAllowedIP('::1');
    }


    public function tearDown(): void
    {
        unlink(self::FILE);
        unset($_SERVER['HTTP_CLIENT_IP']);
    }
}
