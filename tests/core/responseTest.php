<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Http\Response;

class ResponseTest extends TestCase
{

    private $res;


    public function setUp(): void
    {
        $this->res = new Response();
        $this->res->write('This will not appear')->write('Hello')->append(' world');
        $this->res->setCode(301);
    }


    public function testInit()
    {
        $this->assertEquals('Hello world', $this->res->get());
        $this->assertEquals('Hello world', $this->res->send(true));
        $this->assertEquals(301, $this->res->getCode());
        $this->assertEquals(301, http_response_code());
        $this->assertInstanceOf(Response::class, $this->res->setHeader('Content-Type', 'plain/text'));
        $this->assertInstanceOf(Response::class, $this->res->setCookie('foo', 'bar', 'FOREVER'));
        $this->assertInstanceOf(Response::class, $this->res->unsetCookie('foo'));
    }
}
