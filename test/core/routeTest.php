<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Route;

class routeTest extends TestCase
{

    const TEST_MSG = 'Parameter: ';
    const PARAMETER = '15048';


    public function setUp(): void
    {
        Route::get('home', function() {
            return 'hello world';
        });

        Route::get('home2', function() {
            return 'redirected';
        });

        Route::get('home/{id}', function() {
            return self::TEST_MSG . $_GET['id'];
        });

        Route::get('optional/{id2?}', function() {
            return self::TEST_MSG . ($_GET['id2'] ?? '');
        });
    }


    public function testInit()
    {
        $route_func = @Route::getVal('home/' . self::PARAMETER);
        $route2_func = @Route::getVal('optional/');

        $this->assertTrue(Route::exists('home'));
        $this->assertTrue(Route::exists('home/{}'));
        $this->assertNotEmpty(Route::getRoutes());

        //Redirects
        $this->assertEmpty(Route::getRedirects());
        Route::redirect('page1', 'home2');
        $this->assertNotEmpty(Route::getRedirects());

        $redirect_func = @Route::getVal('page1');
        $this->assertEquals('redirected', $redirect_func());

        //Route functions
        $this->assertEquals(self::TEST_MSG . self::PARAMETER, $route_func());
        $this->assertEquals(self::TEST_MSG, $route2_func());

        //Blocked
        $this->assertEmpty(Route::getBlocked());
        Route::block('main_page');
        $this->assertNotEmpty(Route::getBlocked());
        Route::block('home/*');
        $this->assertTrue(Route::isBlocked('home/testing'));
        $this->assertFalse(Route::isBlocked('home2/testing'));
        Route::block('*');
        $this->assertTrue(Route::isBlocked('home'));
    }

}
