<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Cache;
use Wolff\Exception\FileNotFoundException;

class CacheTest extends TestCase
{

    public function setUp(): void
    {
        Cache::init(false);
        Cache::init(true);
        Cache::set('phpunit_testing', '<h2>Hello</h2>');
        Cache::set('another_testing_view', '');
    }


    public function testInit()
    {
        $this->assertTrue(Cache::isEnabled());
        $this->assertTrue(Cache::has('phpunit_testing'));
        $this->assertFalse(Cache::has('non_existent'));
        $this->assertEquals('<h2>Hello</h2>', Cache::get('phpunit_testing'));
        $this->assertTrue(Cache::delete('phpunit_testing'));
        $this->assertFalse(Cache::delete('non_existent'));
        $this->assertFalse(Cache::has('phpunit_testing'));

        $this->expectException(FileNotFoundException::class);
        Cache::get('non_existing_testing_view');
    }


    public function tearDown(): void
    {
        Cache::clear();
    }
}
