<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Core\{Request, Response};

class httpTest extends TestCase
{

    const TEST_MSG = 'Hello world';
    const TEST_SECOND_MSG = 'Lorem Ipsum';
    const RESPONSE_CODE = 200;
    const RESPONSE_REDIRECT = 'https://getwolff.com';


    public function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['msg'] = self::TEST_MSG;
        $_POST['msg'] = self::TEST_MSG;
    }


    public function testRequest()
    {
        // GET
        $this->assertEquals(self::TEST_MSG, Request::get('msg'));
        $this->assertTrue(Request::hasGet('msg'));
        Request::setGet('msg', self::TEST_SECOND_MSG);
        $this->assertEquals(self::TEST_SECOND_MSG, Request::get('msg'));
        Request::unsetGet('msg');
        $this->assertEquals(null, Request::hasGet('msg'));

        // POST
        $this->assertEquals(self::TEST_MSG, Request::post('msg'));
        $this->assertTrue(Request::hasPost('msg'));
        Request::setPost('msg', self::TEST_SECOND_MSG);
        $this->assertEquals(self::TEST_SECOND_MSG, Request::post('msg'));
        Request::unsetPost('msg');
        $this->assertEquals(null, Request::hasPost('msg'));
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
