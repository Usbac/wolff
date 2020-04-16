<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Cache;

class CacheTest extends TestCase
{

    const FILE_CONTENT = '<h2>Hello</h2>';


    public function setUp(): void
    {
        Cache::set('phpunit_testing', self::FILE_CONTENT);
    }


    public function testInit()
    {
        $this->assertTrue(Cache::has('phpunit_testing'));
        $this->assertFalse(Cache::has('non_existent'));
        $this->assertEquals(self::FILE_CONTENT, Cache::get('phpunit_testing'));
        Cache::delete('phpunit_testing');
        $this->assertFalse(Cache::has('phpunit_testing'));
    }
}
