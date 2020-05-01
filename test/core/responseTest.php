<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Http\Response;

class ResponseTest extends TestCase
{
    const RESPONSE_CODE = 301;

    private $response;


    public function setUp(): void
    {
        $this->response = new Response();

        $this->response->write('This will not appear')->write('Hello')->append(' world');
        $this->response->setCode(self::RESPONSE_CODE);
    }


    public function testInit()
    {
        $this->assertEquals('Hello world', $this->response->send(true));
        $this->assertEquals(self::RESPONSE_CODE, http_response_code());
    }
}
