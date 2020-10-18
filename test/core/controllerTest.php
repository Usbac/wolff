<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Controller;
use BadMethodCallException;

class ControllerTest extends TestCase
{

    const CONTROLLER_CONTENT = '<?php
        namespace Controller;

        use Wolff\Core\Controller;

        class phpunit_test extends Controller
        {

            public function index()
            {
                return "from index";
            }

            public function sayHello()
            {
                return $this->getMsg();
            }

            private function getMsg()
            {
                return "Hello in controller";
            }
        }';


    public function setUp(): void
    {
        $controller_file = fopen('../app/controllers/phpunit_test.php', "w") or die();
        fwrite($controller_file, self::CONTROLLER_CONTENT);
        fclose($controller_file);
    }


    public function testInit()
    {
        $this->assertInstanceOf(\Wolff\Core\Controller::class, Controller::get());
        $this->assertInstanceOf(\Wolff\Core\Controller::class, Controller::get('phpunit_test'));
        $this->assertTrue(Controller::exists('phpunit_test'));
        $this->assertFalse(Controller::exists('unit_test/sub/anothercontroller'));
        $this->assertTrue(Controller::hasMethod('phpunit_test', 'sayHello'));
        $this->assertFalse(Controller::hasMethod('phpunit_test', 'getOtherMsg'));
        $this->assertFalse(Controller::hasMethod('non_existent_controller', 'get'));
        $this->assertEquals('Hello in controller', Controller::method('phpunit_test', 'sayHello'));
        $this->assertNull(Controller::get('non_existent_controller'));

        $this->expectException(BadMethodCallException::class);
        Controller::method('phpunit_test', 'non_existent_method');
    }


    public function tearDown(): void
    {
        unlink('../app/controllers/phpunit_test.php');
    }
}
