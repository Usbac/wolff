<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Config;

class ConfigTest extends TestCase
{

    private $data;


    public function setUp(): void
    {
        $this->data = include('../system/config.php');
        $this->data['env_file'] = 'test/.env';
        $this->data['env_override'] = true;

        Config::init($this->data);
    }


    public function testInit()
    {
        $keys = array_keys($this->data);

        foreach ($keys as $key) {
            $this->assertConfigKey($key);
        }
    }


    private function assertConfigKey(string $key)
    {
        // Environment key
        $env_key = \strtoupper($key);
        if (isset($_ENV[$env_key])) {
            $this->assertEquals($_ENV[$env_key], Config::get($key));

            return;
        }

        // Config key
        if (is_array($this->data[$key])) {
            $this->assertTrue($this->arrayEquals($this->data[$key], Config::get($key)));
        } else {
            $this->assertEquals($this->data[$key], Config::get($key));
        }
    }


    private function arrayEquals(array $a, array $b)
    {
        if (count(array_diff_assoc($a, $b))) {
            return false;
        }

        foreach ($a as $key => $val) {
            if ($val !== $b[$key]) {
                return false;
            }
        }

        return true;
    }
}
