<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Config;
use Wolff\Exception\FileNotReadableException;

class ConfigTest extends TestCase
{

    private $data;


    public function setUp(): void
    {
        $this->data = include('../system/config.php');
        $this->data['env_file'] = 'test/.env';
        $this->data['env_override'] = true;

        $env_file = fopen('../test/testing.env', 'w');
        fwrite($env_file, "LANGUAGE='spanish'\nFOO=null #comment\nTRUE=true");
        fclose($env_file);

        Config::init($this->data);
    }


    public function testInit()
    {
        foreach (array_keys($this->data) as $key) {
            $this->assertConfigKey($key);
        }

        $this->assertIsArray(Config::get());

        $this->expectException(FileNotReadableException::class);

        Config::init([
            'env_file' => 'test/non_existent.env'
        ]);
    }


    public function testEnv()
    {
        Config::init([
            'env_file'     => 'test/testing.env',
            'env_override' => true
        ]);

        $this->assertEquals($_ENV['LANGUAGE'], Config::get('language'));
        $this->assertNull($_ENV['FOO']);
        $this->assertTrue($_ENV['TRUE']);
    }


    public function tearDown(): void
    {
        unlink('../test/testing.env');
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
