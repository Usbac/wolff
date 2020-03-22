<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\View;

class viewTest extends TestCase
{

    const VIEW_NAME = 'phpunit_testing';
    const VIEW_PATH = CONFIG['app_dir'] . '/' . CORE_CONFIG['views_dir'] . '/' . self::VIEW_NAME . '.' . CORE_CONFIG['views_format'];
    const VIEW_CONTENT = '<h1>{! $msg !}</h1><br/>';
    const VIEW_CONTENT_RENDERED = '<h1>Hello world</h1><br/>';

    private $non_existent;
    private $data;


    public function setUp(): void
    {
        $this->non_existent = 'home/sub/phpunit/' . rand(0, 10000);
        $this->data = [
            'msg' => 'Hello world'
        ];

        $view_file = fopen(self::VIEW_PATH, "w") or die();
        fwrite($view_file, self::VIEW_CONTENT);
        fclose($view_file);
    }


    public function testInit()
    {
        $this->assertTrue(View::exists(self::VIEW_NAME));
        $this->assertFalse(View::exists($this->non_existent));
        $this->assertEquals(self::VIEW_CONTENT, View::getSource(self::VIEW_NAME, $this->data, false));
        $this->assertEquals(self::VIEW_CONTENT_RENDERED, View::getRender(self::VIEW_NAME, $this->data, false));

        unlink(self::VIEW_PATH);
    }

}
