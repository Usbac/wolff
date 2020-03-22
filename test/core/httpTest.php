<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use \Wolff\Core\Http\{Request, Response};

class httpTest extends TestCase
{

    const TEST_MSG = 'Hello world';
    const TEST_NAME = 'Evan you';
    const TEST_USERNAME = 'usbac';
    const TEST_PASSWORD = 'notapassword';
    const TEST_SECOND_MSG = 'Lorem Ipsum';
    const RESPONSE_CODE = 200;
    const RESPONSE_REDIRECT = 'https://getwolff.com';

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

        $this->request = new Request(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER
        );
    }


    public function testRequest()
    {
        $this->assertTrue($this->request->hasParam('msg'));
        $this->assertFalse($this->request->hasParam('another'));
        $this->assertEquals($this->request->param('name'), self::TEST_NAME);
        $this->assertEquals($this->request->param(), [
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
    }


    public function testResponse()
    {
        $response = new Response();
        $response->header('Content-Type', 'text/html; charset=utf-8')
            ->setCode(self::RESPONSE_CODE)
            ->redirect(self::RESPONSE_REDIRECT);
        $headers = [
            'Content-Type' => 'text/html; charset=utf-8'
        ];

        $this->assertEquals($headers, $response->getHeaders());
        $response->remove('Content-Type');
        $this->assertEmpty($response->getHeaders());
        $this->assertEquals(self::RESPONSE_CODE, $response->getCode());
        $this->assertEquals(self::RESPONSE_REDIRECT, $response->getRedirect());
    }

}
