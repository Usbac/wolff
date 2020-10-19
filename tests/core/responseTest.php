<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Http\Response;

class ResponseTest extends TestCase
{

    private $response;


    public function setUp(): void
    {
        $this->response = new Response();
        $this->response->write('This will not appear')->write('Hello')->append(' world');
        $this->response->setCode(301);
    }


    public function testInit()
    {
        $this->assertEquals('Hello world', $this->response->get());
        $this->assertEquals('Hello world', $this->response->send(true));
        $this->assertEquals(301, http_response_code());
        $this->assertInstanceOf(Response::class, $this->response->setHeader('Content-Type', 'plain/text'));
        $this->assertInstanceOf(Response::class, $this->response->setCookie('foo', 'bar', 'FOREVER'));
        $this->assertInstanceOf(Response::class, $this->response->unsetCookie('foo'));
    }
}
