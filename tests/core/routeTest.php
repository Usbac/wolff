<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Core\Route;

class routeTest extends TestCase
{

    const TEST_MSG = 'Parameter: ';
    const PARAMETER = '15048';


    public function setUp(): void
    {
        Route::get('home', function() {
            self::TEST_MSG;
        }, 301);

        Route::get('home/{id}', function() {
            return self::TEST_MSG . $_GET['id'];
        }, 301);

        Route::get('optional/{id2?}', function() {
            return self::TEST_MSG . ($_GET['id2'] ?? '');
        }, 301);

        Route::block('main_page');
        Route::redirect('page1', 'home');
    }


    public function testInit()
    {
        $route_func = @Route::getFunc('home/' . self::PARAMETER);
        $route2_func = @Route::getFunc('optional/');

        $this->assertTrue(Route::exists('home'));
        $this->assertTrue(Route::exists('home/{}'));
        $this->assertNotEmpty(Route::getRoutes());
        $this->assertNotEmpty(Route::getBlocked());
        $this->assertNotEmpty(Route::getRedirects());
        $this->assertEquals(self::TEST_MSG . self::PARAMETER, $route_func());
        $this->assertEquals(self::TEST_MSG, $route2_func());
    }

}
