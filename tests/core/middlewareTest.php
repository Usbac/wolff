<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Middleware;
use Wolff\Exception\InvalidArgumentException;

class MiddlewareTest extends TestCase
{

    private function addMiddlewares(): void
    {
        Middleware::before('user/panel', function ($req, $next) {
            $next();
            return 'inside';
        });

        Middleware::before('user/panel', function ($req, $next) {
            return ' user panel';
        });

        Middleware::before('admin/*', function ($req, $next) {
            $next();
            return '1';
        });

        Middleware::before('admin/*', function ($req, $next) {
            return '2';
        });

        Middleware::before('admin/*', function ($req, $next) {
            return 'You shouldn\'t be here';
        });

        Middleware::after('posts/*', function ($req, $next) {
            $next();
            return 'Lorem';
        });

        Middleware::after('posts/*', function ($req, $next) {
            return 'Ipsum';
        });

        Middleware::before('chain', function ($req, $next) {
            $next();
            return 'Hello';
        });

        Middleware::before('chain', function ($req, $next) {
            $next();
            return ' how';
        });

        Middleware::before('chain', function ($req, $next) {
            $next();
            return ' are';
        });

        Middleware::before('chain', function ($req, $next) {
            return ' you?';
        });
    }


    public function testInit()
    {
        $this->assertEquals('', Middleware::loadBefore('user'));
        $this->addMiddlewares();
        $this->assertEquals('inside user panel', Middleware::loadBefore('user/panel'));
        $this->assertEquals('12', Middleware::loadBefore('admin/posts'));
        $this->assertEquals('LoremIpsum', Middleware::loadAfter('posts/start'));
        $this->assertEquals('LoremIpsum', Middleware::loadAfter('posts/page/2'));
        $this->assertEquals('Hello how are you?', Middleware::loadBefore('chain//'));
        $this->assertNull(Middleware::non_existent_method());
        $this->expectException(InvalidArgumentException::class);
        Middleware::before(12345);
        Middleware::before('url', 'function');
    }
}
