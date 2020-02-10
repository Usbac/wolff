<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Core\Maintenance;

class maintenanceTest extends TestCase
{

    const ALLOWED_IP = '192.168.1.2';
    const MAINTENANCE_FILE = CONFIG['system_dir'] . '/definitions/maintenance_whitelist.txt';


    public function setUp(): void
    {
        Maintenance::createFile();
        Maintenance::addAllowedIP(self::ALLOWED_IP);
    }


    public function testInit()
    {
        $this->assertFileExists(self::MAINTENANCE_FILE);
        $this->assertContains(self::ALLOWED_IP, Maintenance::getAllowedIPs());
        Maintenance::removeAllowedIP(self::ALLOWED_IP);

        $allowed_ips = Maintenance::getAllowedIPs();
        if (!$allowed_ips) {
            $allowed_ips = [];
        }

        $this->assertNotContains(self::ALLOWED_IP, $allowed_ips);
    }

}
