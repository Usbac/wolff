<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Maintenance;

class MaintenanceTest extends TestCase
{

    const ALLOWED_IP = '192.168.1.2';
    const ANOTHER_ALLOWED_IP = '::1';
    const FILE = '../system/testing_maintenance_whitelist.txt';


    public function setUp():void
    {
        Maintenance::setFile('system/testing_maintenance_whitelist.txt');
        Maintenance::addAllowedIP(self::ALLOWED_IP);
        Maintenance::addAllowedIP(self::ANOTHER_ALLOWED_IP);
        Maintenance::addAllowedIP('');
    }


    public function testInit()
    {
        $this->assertFileExists(self::FILE);
        $this->assertContains(self::ALLOWED_IP, Maintenance::getAllowedIPs());
        $this->assertContains(self::ANOTHER_ALLOWED_IP, Maintenance::getAllowedIPs());
        $this->assertNotContains('192.168.1.3', Maintenance::getAllowedIPs());
        Maintenance::removeAllowedIP(self::ALLOWED_IP);
        $this->assertNotContains(self::ALLOWED_IP, Maintenance::getAllowedIPs());
    }


    public function tearDown():void
    {
        unlink(self::FILE);
    }
}
