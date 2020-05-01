<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Controller;

class ControllerTest extends TestCase
{

    const METHOD_NAME = 'sayHello';
    const CONTROLLER_NAME = 'phpunit_test';
    const CONTROLLER_PATH = '../app/controllers/' . self::CONTROLLER_NAME . '.php';
    const CONTROLLER_CONTENT = '<?php
        namespace Controller;

        use Wolff\Core\Controller;

        class phpunit_test extends Controller
        {

            public function index()
            {
                return "from index";
            }

            public function ' . self::METHOD_NAME . '()
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
        $controller_file = fopen(self::CONTROLLER_PATH, "w") or die();
        fwrite($controller_file, self::CONTROLLER_CONTENT);
        fclose($controller_file);
    }


    public function testInit()
    {
        $this->assertInstanceOf(\Wolff\Core\Controller::class, Controller::get(self::CONTROLLER_NAME));
        $this->assertTrue(Controller::exists(self::CONTROLLER_NAME));
        $this->assertFalse(Controller::exists('unit_test/sub/anothercontroller'));
        $this->assertTrue(Controller::hasMethod(self::CONTROLLER_NAME, self::METHOD_NAME));
        $this->assertFalse(Controller::hasMethod(self::CONTROLLER_NAME, 'getOtherMsg'));
        $this->assertEquals('Hello in controller', Controller::method(self::CONTROLLER_NAME, self::METHOD_NAME));
    }


    public function tearDown():void
    {
        unlink(self::CONTROLLER_PATH);
    }
}
