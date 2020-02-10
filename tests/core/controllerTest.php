<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Core\Controller;

class controllerTest extends TestCase
{

    const METHOD_NAME = 'sayHello';
    const CONTROLLER_NAME = 'phpunit_test';
    const CONTROLLER_PATH = CONFIG['app_dir'] . '/controllers/' . self::CONTROLLER_NAME . '.php';
    const CONTROLLER_CONTENT = '<?php
        namespace Controller;

        use Core\Controller;

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
        $func = function() {
            return 'Hello from closure';
        };

        $this->assertTrue(Controller::exists(self::CONTROLLER_NAME));
        $this->assertTrue(Controller::methodExists(self::CONTROLLER_NAME, self::METHOD_NAME));
        $this->assertEquals('Hello from closure', Controller::closure($func));
        $this->assertEquals('Hello in controller', Controller::method(self::CONTROLLER_NAME, self::METHOD_NAME));

        unlink(self::CONTROLLER_PATH);
    }

}
