<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Language;
use Wolff\Exception\InvalidLanguageException;

class LanguageTest extends TestCase
{

    const CONTENT = "<?php
        return [
            'title' => 'Wolff framework',
            'msg'   => 'hello world'
        ];";

    const INVALID_CONTENT = "<?php
        return 10;";


    public function setUp(): void
    {
        if (!file_exists('../app/languages/phpunit')) {
            mkdir('../app/languages/phpunit');
        }

        $lang_file = fopen('../app/languages/phpunit/testing.php', 'w');
        fwrite($lang_file, self::CONTENT);
        fclose($lang_file);

        $invalid_lang_file = fopen('../app/languages/phpunit/testing_invalid.php', 'w');
        fwrite($invalid_lang_file, self::INVALID_CONTENT);
        fclose($invalid_lang_file);

        Language::setDefault('phpunit');
    }


    public function testInit()
    {
        $non_existent = 'testing_' . rand(0, 10000);
        $language_array = [
            'title' => 'Wolff framework',
            'msg'   => 'hello world'
        ];

        $this->assertTrue(Language::exists('testing'));
        $this->assertTrue(Language::exists('testing', 'phpunit'));
        $this->assertFalse(Language::exists($non_existent, 'phpunit'));
        $this->assertEquals($language_array, Language::get('testing'));
        $this->assertEquals('Wolff framework', Language::get('testing.title', 'phpunit'));
        $this->assertNull(Language::get('testing2', 'phpunit'));
        $this->assertNull(Language::get('testing.title2', 'phpunit'));

        $this->expectException(InvalidLanguageException::class);
        Language::get('testing_invalid');
    }


    public function tearDown(): void
    {
        unlink('../app/languages/phpunit/testing.php');
        unlink('../app/languages/phpunit/testing_invalid.php');
        rmdir('../app/languages/phpunit');
    }
}
