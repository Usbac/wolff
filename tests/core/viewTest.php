<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\View;

class ViewTest extends TestCase
{

    private $non_existent;

    private $data;


    public function setUp(): void
    {
        $this->non_existent = 'home/sub/phpunit/' . rand(0, 10000);
        $this->data = [
            'msg' => 'Hello world'
        ];

        $view_file = fopen('../app/views/phpunit_testing_view.wlf', "w") or die();
        fwrite($view_file, '<h1>{! $msg !}</h1><br/>');
        fclose($view_file);

        $view_file_2 = fopen('../app/views/phpunit_testing_view_ext.html', "w") or die();
        fwrite($view_file_2, '<h1>{! $msg !}</h1><br/>');
        fclose($view_file_2);

        $view_file_3 = fopen('../app/views/phpunit_testing_view_empty.wlf', "w") or die();
        fwrite($view_file_3, '');
        fclose($view_file_3);
    }


    public function testInit()
    {
        $this->assertTrue(View::exists('phpunit_testing_view'));
        $this->assertFalse(View::exists($this->non_existent));
        $this->assertEquals('<h1>{! $msg !}</h1><br/>', View::getSource('phpunit_testing_view', $this->data, false));
        $this->assertEquals('<h1><?php echo $msg ?></h1><br/>', View::get('phpunit_testing_view'));
        $this->assertEquals('<h1>Hello world</h1><br/>', View::getRender('phpunit_testing_view', $this->data, false));

        $this->assertTrue(View::exists('phpunit_testing_view_ext.html'));
        $this->assertFalse(View::exists('phpunit_testing_view_ext'));
        $this->assertEquals('<h1>{! $msg !}</h1><br/>', View::getSource('phpunit_testing_view_ext.html', $this->data, false));
        $this->assertEquals('<h1>Hello world</h1><br/>', View::getRender('phpunit_testing_view_ext.html', $this->data, false));
        $this->assertNull(View::render('phpunit_testing_view_empty'));
    }


    public function tearDown(): void
    {
        unlink('../app/views/phpunit_testing_view.wlf');
        unlink('../app/views/phpunit_testing_view_ext.html');
        unlink('../app/views/phpunit_testing_view_empty.wlf');
    }
}
