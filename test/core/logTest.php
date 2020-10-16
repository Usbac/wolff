<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Log;

class LogTest extends TestCase
{

    private $log;

    private $expected_content;


    public function setUp(): void
    {
        $this->log = new Log();
        $this->log->setFolder('test/logs');
        $this->log->setDateFormat('H:i');

        $this->log->info('Hello world');
        $this->log->notice('Inside tests');
        $this->log->warning('The password \'{password}\' is invalid', [
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
        $this->log->setStatus(false);
        $this->assertNull($this->log->otherStatus());
        $this->assertNull($this->log->notice('inside test'));
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
