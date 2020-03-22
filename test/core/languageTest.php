<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Language;

class languageTest extends TestCase
{

    const LANGUAGE_FOLDER = CONFIG['app_dir'] . '/' . CORE_CONFIG['languages_dir'] . '/phpunit/';
    const LANGUAGE_PATH = self::LANGUAGE_FOLDER . 'testing.php';
    const LANGUAGE_CONTENT = "<?php
        return [
            'title' => 'Wolff framework',
            'msg'   => 'hello world'
        ];";


    public function testInit()
    {
        $this->createLanguageFile();
        $non_existent = 'testing_' . rand(0, 10000);
        $language_array = [
            'title' => 'Wolff framework',
            'msg'   => 'hello world'
        ];

        $this->assertTrue(Language::exists('testing', 'phpunit'));
        $this->assertFalse(Language::exists($non_existent, 'phpunit'));
        $this->assertEquals($language_array, Language::get('testing', 'phpunit'));
        $this->assertEquals('Wolff framework', Language::get('testing.title', 'phpunit'));

        unlink(self::LANGUAGE_PATH);
        rmdir(self::LANGUAGE_FOLDER);
    }


    private function createLanguageFile()
    {
        if (!file_exists(self::LANGUAGE_FOLDER)) {
            mkdir(self::LANGUAGE_FOLDER);
        }

        $language_file = fopen(self::LANGUAGE_PATH, "w") or die();
        fwrite($language_file, self::LANGUAGE_CONTENT);
        fclose($language_file);
    }

}
