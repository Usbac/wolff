<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Route;

class RouteTest extends TestCase
{


    public function setUp(): void
    {
        Route::get('/', function () {
            return 'in root';
        });

        Route::get('home', function () {
            return 'hello world';
        });

        Route::get('home2', function () {
            return 'redirected';
        });

        Route::get('home/{id}', function () {
            return 'Parameter: ' . $_GET['id'];
        });

        Route::get('optional/{id2?}', function () {
            return 'Parameter: ' . ($_GET['id2'] ?? '');
        });

        Route::any('blog/{page?}/dark', function () {
            return 'in page ' . $_GET['page'];
        });

        Route::code(404, function ($req, $res) {
            $req->foo = 'bar';
            $res->foo = 'bar';
        });
    }


    public function testInit()
    {
        $this->assertTrue(Route::exists('home'));
        $this->assertTrue(Route::exists('home/{}'));
        $this->assertNotEmpty(Route::getRoutes());

        //Redirects
        $this->assertEmpty(Route::getRedirects());
        Route::redirect('page1', 'home2');
        $this->assertNotEmpty(Route::getRedirects());
        $redirection = [
            'destiny' => 'home2',
            'code'    => 301
        ];
        $this->assertEquals($redirection, Route::getRedirection('page1'));
        $this->assertNull(Route::getRedirection('invalid_redirect'));
        $this->assertEquals('redirected', @Route::getFunction('home2')());

        //Route functions
        $this->assertNull(Route::invalid_method());
        $this->assertEquals('in root', @Route::getFunction('/')());
        $this->assertEquals('Parameter: ' . '15048', @Route::getFunction('home/15048')());
        $this->assertEquals('Parameter: ', @Route::getFunction('optional/')());
        $this->assertEquals('Parameter: ' . '123', @Route::getFunction('optional/123')());
        $this->assertEquals('in page 12', @Route::getFunction('blog/12/dark')());
        $this->assertNull(@Route::getFunction('blog/12/'));
        $this->assertNull(@Route::getFunction('blog/12/white'));
        $this->assertNull(@Route::getFunction('home/123/another'));

        //Code
        http_response_code(202);
        $req = new \Wolff\Core\Http\Request([], [], [], [], []);
        $res = new \Wolff\Core\Http\Response;
        $this->assertNull(Route::execCode($req, $res));
        http_response_code(404);
        Route::execCode($req, $res);
        $this->assertEquals('bar', $req->foo);
        $this->assertEquals('bar', $res->foo);

        //Blocked
        $this->assertEmpty(Route::getBlocked());
        Route::block('main_page');
        $this->assertNotEmpty(Route::getBlocked());
        Route::block('home/*');
        $this->assertTrue(Route::isBlocked('home/testing'));
        $this->assertFalse(Route::isBlocked('home2/testing'));
        Route::block('*');
        $this->assertTrue(Route::isBlocked('home'));
        $this->assertTrue(Route::isBlocked('another_route'));
    }
}
