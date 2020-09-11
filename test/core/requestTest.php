<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Http\Request;
use Wolff\Exception\InvalidArgumentException;

class RequestTest extends TestCase
{

    private $request;


    public function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = [
            'name' => 'Evan you',
            'msg'  => 'Hello world'
        ];

        $_POST = [
            'username' => 'usbac',
            'password' => 'notapassword'
        ];

        $_FILES = [
            'image' => [
                'name'     => 'file.jpg',
                'type'     => 'image/jpeg',
                'tmp_name' => '/tmp/php/php6hst32',
                'size'     => 98174
            ]
        ];

        $_SERVER['REQUEST_URI'] = 'home';
        $_SERVER['HTTPS'] = false;
        $_SERVER['HTTP_CONTENT_TYPE'] = 'plain/text';

        $this->request = new Request(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            [
                'user_id' => 'wolf123#'
            ]
        );
    }


    public function testInit()
    {
        $this->assertTrue($this->request->hasQuery('msg'));
        $this->assertFalse($this->request->hasQuery('another'));
        $this->assertEquals('Evan you', $this->request->query('name'));
        $this->assertEquals([
            'name' => 'Evan you',
            'msg'  => 'Hello world'
        ], $this->request->query());

        $this->assertTrue($this->request->has('username'));
        $this->assertFalse($this->request->has('username2'));
        $this->assertEquals('usbac', $this->request->body('username'));
        $this->assertEquals([
            'username' => 'usbac',
            'password' => 'notapassword'
        ], $this->request->body());

        $this->assertNull($this->request->fileOptions([
            'dir'        => '/',
            'extensions' => 'jpg',
            'max_size'   => 1024
        ]));

        $this->expectException(InvalidArgumentException::class);
        $this->request->fileOptions([
            'dir'        => 123,
            'extensions' => 'jpg',
            'max_size'   => 1024
        ]);

        $this->assertTrue($this->request->hasFile('image'));
        $this->assertFalse($this->request->hasFile('another_image'));
        $this->assertInstanceOf(\Wolff\Core\Http\File::class, $this->request->file('image'));
        $this->assertNotEmpty($this->request->file());
        $this->assertNotEmpty($this->request->cookie());

        $this->assertEquals('wolf123#', $this->request->cookie()['user_id']);
        $this->assertEquals('wolf123#', $this->request->cookie('user_id'));
        $this->assertTrue($this->request->hasCookie('user_id'));
        $this->assertFalse($this->request->hasCookie('Expires'));

        $this->assertNotEmpty($this->request->getHeader());
        $this->assertEquals($_SERVER['HTTP_CONTENT_TYPE'], $this->request->getHeader('Content-Type'));

        $this->assertEquals($this->getUri(), $this->request->getUri());
        $this->assertEquals($_SERVER['REQUEST_URI'], $this->request->getFullUri());
        $this->assertEquals($_SERVER['REQUEST_METHOD'], $this->request->getMethod());
        $this->assertFalse($this->request->isSecure());
    }


    private function getUri()
    {
        return substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
    }
}
