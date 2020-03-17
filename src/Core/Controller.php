<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Controller
{

    const NAMESPACE = 'Controller\\';
    const EXISTS_ERROR = 'The controller class \'%s\' doesn\'t have a \'%s\' method';
    const PATH_FORMAT = '%s/' . CORE_CONFIG['controllers_dir'] . '/%s.php';


    /**
     * General data of the controller.
     *
     * @var array
     */
    protected $data;


    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->data = [];
    }


    /**
     * Instantiate the controller with the giving name and
     * call its index method if it exists
     *
     * @param  string  $dir  the controller name
     *
     * @return \Wolff\Core\Controller the controller
     */
    public static function call(string $dir)
    {
        $dir = Str::sanitizePath($dir);

        //load controller default function and return it
        if (($controller = Factory::controller($dir)) === false) {
            return false;
        }

        if (method_exists($controller, 'index')) {
            $controller->index();
        }

        return $controller;
    }


    /**
     * Returns the return value of the controller's method
     * or null in case of errors
     *
     * @param  string  $controller_name  the controller name
     * @param  string  $method  the controller method
     * @param  array  $params  the method arguments
     *
     * @return mixed the return value of the controller's method
     * or null in case of errors
     */
    public static function method(string $controller_name, string $method = 'index', array $params = [])
    {
        $controller = Factory::controller($controller_name);

        if (method_exists($controller, $method)) {
            return call_user_func_array([$controller, $method], $params);
        }

        throw new \Error(sprintf(self::EXISTS_ERROR, $controller_name, $method));

        return null;
    }


    /**
     * Append a function to the \Wolff\Core\Controller class and execute it
     *
     * @param  mixed  $closure  the anonymous function
     */
    public static function closure($closure)
    {
        if (is_string($closure)) {
            self::call($closure);
            return null;
        }

        $controller = Factory::controller();
        return $closure->bindTo($controller, $controller)();
    }


    /**
     * Returns the complete path of the controller
     *
     * @param  string  $dir  the directory of the controller
     *
     * @return string the complete path of the controller
     */
    public static function getPath(string $dir)
    {
        return sprintf(self::PATH_FORMAT, CONFIG['app_dir'], $dir);
    }


    /**
     * Returns true if the controller exists in the indicated directory,
     * false otherwise
     *
     * @param  string  $dir  the directory of the controller
     *
     * @return boolean true if the controller exists, false otherwise
     */
    public static function exists(string $dir)
    {
        return file_exists(self::getPath($dir));
    }


    /**
     * Returns true if the controller's method exists, false otherwise
     *
     * @param  string  $controller_name  the controller name
     * @param  string  $method  the controller method name
     *
     * @return boolean true if the controller's method exists, false otherwise
     */
    public static function methodExists(string $controller_name, string $method)
    {
        $class = self::NAMESPACE . str_replace('/', '\\', $controller_name);

        if (!class_exists($class)) {
            return false;
        }

        $class = new \ReflectionClass($class);

        if (!$class->hasMethod($method)) {
            return false;
        }

        return true;
    }

}
