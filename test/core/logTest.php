<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Log;

class LogTest extends TestCase
{

    private $created_file;

    private $expected_content;


    public function setUp(): void
    {
        Log::setFolder('test/logs');
        Log::setDateFormat('H:i');

        Log::info('Hello world');
        Log::notice('Inside tests');
        Log::warning('The password \'{password}\' is invalid', [
            'username' => 'usbac',
            'password' => '123456'
        ]);

        $this->file_path = '../test/logs/' . date('m-d-Y') . '.log';
        $this->expected_content = $this->getExpectedContent();
    }


    public function testInit()
    {
        $this->assertFileExists($this->file_path);
        $this->assertEquals($this->expected_content, \file_get_contents($this->file_path));
        Log::setStatus(false);
        $this->assertNull(Log::otherStatus());
        $this->assertNull(Log::notice('inside test'));
    }


    public function tearDown(): void
    {
        unlink($this->file_path);
        rmdir('../test/logs');
    }


    private function getExpectedContent()
    {
        $expected_content = "[%s][] Info: Hello world\n[%s][] Notice: Inside tests\n[%s][] Warning: The password '123456' is invalid\n";
        return str_replace('%s', date('H:i'), $expected_content);
    }
}
