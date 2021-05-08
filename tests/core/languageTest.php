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
        $lang = new Language;

        $this->assertTrue($lang->exists('testing'));
        $this->assertTrue($lang->exists('testing', 'phpunit'));
        $this->assertFalse($lang->exists('testing_non_existent', 'phpunit'));
        $this->assertEquals([
            'title' => 'Wolff framework',
            'msg'   => 'hello world',
        ], $lang->get('testing'));
        $this->assertEquals('Wolff framework', $lang->get('testing.title', 'phpunit'));
        $this->assertNull($lang->get('testing2', 'phpunit'));
        $this->assertNull($lang->get('testing.title2', 'phpunit'));

        $this->expectException(InvalidLanguageException::class);
        $lang->get('testing_invalid');
    }


    public function tearDown(): void
    {
        unlink('../app/languages/phpunit/testing.php');
        unlink('../app/languages/phpunit/testing_invalid.php');
        rmdir('../app/languages/phpunit');
    }
}
