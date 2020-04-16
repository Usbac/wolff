<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Maintenance;

class MaintenanceTest extends TestCase
{

    const ALLOWED_IP = '192.168.1.2';
    const MAINTENANCE_FILE = '../system/maintenance_whitelist.txt';


    public function setUp(): void
    {
        Maintenance::setFile();
        Maintenance::addAllowedIP(self::ALLOWED_IP);
        Maintenance::addAllowedIP('');
    }


    public function testInit()
    {
        $this->assertFileExists(self::MAINTENANCE_FILE);
        $this->assertContains(self::ALLOWED_IP, Maintenance::getAllowedIPs());
        $this->assertNotContains('192.168.1.3', Maintenance::getAllowedIPs());
        Maintenance::removeAllowedIP(self::ALLOWED_IP);
        $this->assertNotContains(self::ALLOWED_IP, Maintenance::getAllowedIPs());
    }
}
