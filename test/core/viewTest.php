<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\View;

class ViewTest extends TestCase
{

    const VIEW_NAME = 'phpunit_testing_view';
    const FILE = '../app/views/' . self::VIEW_NAME . '.wlf';
    const CONTENT = '<h1>{! $msg !}</h1><br/>';
    const CONTENT_RENDERED = '<h1>Hello world</h1><br/>';

    private $non_existent;
    private $data;


    public function setUp(): void
    {
        $this->non_existent = 'home/sub/phpunit/' . rand(0, 10000);
        $this->data = [
            'msg' => 'Hello world'
        ];

        $view_file = fopen(self::FILE, "w") or die();
        fwrite($view_file, self::CONTENT);
        fclose($view_file);
    }


    public function testInit()
    {
        $this->assertTrue(View::exists(self::VIEW_NAME));
        $this->assertFalse(View::exists($this->non_existent));
        $this->assertEquals(self::CONTENT, View::getSource(self::VIEW_NAME, $this->data, false));
        $this->assertEquals(self::CONTENT_RENDERED, View::getRender(self::VIEW_NAME, $this->data, false));
    }


    public function tearDown():void
    {
        unlink(self::FILE);
    }
}
