<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Http\Request;

class RequestTest extends TestCase
{

    const TEST_MSG = 'Hello world';
    const TEST_NAME = 'Evan you';
    const TEST_USERNAME = 'usbac';
    const TEST_PASSWORD = 'notapassword';

    private $request;


    public function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = [
            'name' => self::TEST_NAME,
            'msg'  => self::TEST_MSG
        ];

        $_POST = [
            'username' => self::TEST_USERNAME,
            'password' => self::TEST_PASSWORD
        ];

        $_FILES = [
            'image' => [
                'name'     => 'file.jpg',
                'type'     => 'image/jpeg',
                'tmp_name' => '/tmp/php/php6hst32',
                'size'     => 98174
            ]
        ];

        $this->request = new Request(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_FILES
        );
    }


    public function testInit()
    {
        $this->assertTrue($this->request->hasQuery('msg'));
        $this->assertFalse($this->request->hasQuery('another'));
        $this->assertEquals($this->request->query('name'), self::TEST_NAME);
        $this->assertEquals($this->request->query(), [
            'name' => self::TEST_NAME,
            'msg'  => self::TEST_MSG
        ]);

        $this->assertTrue($this->request->has('username'));
        $this->assertFalse($this->request->has('username2'));
        $this->assertEquals($this->request->body('username'), self::TEST_USERNAME);
        $this->assertEquals($this->request->body(), [
            'username' => self::TEST_USERNAME,
            'password' => self::TEST_PASSWORD
        ]);

        $this->assertTrue($this->request->hasFile('image'));
        $this->assertFalse($this->request->hasFile('another_image'));
        $this->assertInstanceOf(\Wolff\Core\Http\File::class, $this->request->file('image'));

        $this->assertEquals($_SERVER['REQUEST_METHOD'], $this->request->getMethod());
        $this->assertFalse($this->request->isSecure());
    }
}
