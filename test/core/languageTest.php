<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Language;

class LanguageTest extends TestCase
{

    const FOLDER =  '../app/languages/phpunit';
    const FILE = self::FOLDER . '/testing.php';
    const CONTENT = "<?php
        return [
            'title' => 'Wolff framework',
            'msg'   => 'hello world'
        ];";


    public function setUp():void
    {
        if (!file_exists(self::FOLDER)) {
            mkdir(self::FOLDER);
        }

        $language_file = fopen(self::FILE, "w") or die();
        fwrite($language_file, self::CONTENT);
        fclose($language_file);
    }


    public function testInit()
    {
        $non_existent = 'testing_' . rand(0, 10000);
        $language_array = [
            'title' => 'Wolff framework',
            'msg'   => 'hello world'
        ];

        $this->assertTrue(Language::exists('testing', 'phpunit'));
        $this->assertFalse(Language::exists($non_existent, 'phpunit'));
        $this->assertEquals($language_array, Language::get('testing', 'phpunit'));
        $this->assertEquals('Wolff framework', Language::get('testing.title', 'phpunit'));
        $this->assertNull(Language::get('testing.title2', 'phpunit'));
    }


    public function tearDown():void
    {
        unlink(self::FILE);
        rmdir(self::FOLDER);
    }
}
