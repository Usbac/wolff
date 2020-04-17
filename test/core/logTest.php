<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Log;

class LogTest extends TestCase
{

    const FOLDER = 'test/logs';
    const DATE_FORMAT = 'H:i';

    private $created_file;

    private $expected_content;


    public function setUp():void
    {
        Log::setFolder(self::FOLDER);
        Log::setDateFormat(self::DATE_FORMAT);

        Log::info('Hello world');
        Log::notice('Inside tests');
        Log::warning('The password \'{password}\' is invalid', [
            'username' => 'usbac',
            'password' => '123456'
        ]);

        $this->file_path = '../' . self::FOLDER . '/' . date('m-d-Y') . '.log';
        $this->expected_content = $this->getExpectedContent();
    }


    public function testInit()
    {
        $this->assertFileExists($this->file_path);
        $this->assertEquals($this->expected_content, \file_get_contents($this->file_path));
    }


    public function tearDown():void
    {
        unlink($this->file_path);
        rmdir('../' . self::FOLDER);
    }


    private function getExpectedContent()
    {
        $expected_content = "[%s][] Info: Hello world\n[%s][] Notice: Inside tests\n[%s][] Warning: The password '123456' is invalid\n";
        return str_replace('%s', date(self::DATE_FORMAT), $expected_content);
    }
}
