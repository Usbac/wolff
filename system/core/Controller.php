<?php

namespace Core;

use Utilities\Str;

class Controller
{

    const NAMESPACE = 'Controller\\';
    const METHOD_SEPARATOR = '@';
    const FUNCTION_FORMAT = '{controller}' . self::METHOD_SEPARATOR . '{function}';
    const EXISTS_ERROR = "The controller class '{controller}' doesn't have a '{function}' method";
    const PATH_FORMAT = '{app}controllers/{dir}.php';


    /**
     * General data of the controller.
     *
     * @var array
     */
    protected $data;


    public function __construct()
    {
        $this->data = [];
    }


    public static function call(string $dir, array $params = [])
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
     * @param  string  $name  the controller's method name
     * Must have the following format: controller_name@method_name
     * @param  array  $params  the method arguments
     *
     * @return mixed the return value of the controller's method
     * or null in case of errors
     */
    public static function method(string $name, array $params = [])
    {
        if (!($controller_name = Str::before($name, self::METHOD_SEPARATOR))) {
            return null;
        }

        $controller = Factory::controller($controller_name);
        $function = Str::after($name, self::METHOD_SEPARATOR);

        if (method_exists($controller, $function)) {
            return call_user_func_array([$controller, $function], $params);
        }

        Log::error(Str::interpolate(self::EXISTS_ERROR, [
            'controller' => $controller_name,
            'function'   => $function
        ]));

        return null;
    }


    /**
     * Append a function to the Core\Controller class and execute it
     *
     * @param  mixed  $closure  the anonymous function
     */
    public static function closure($closure)
    {
        if (is_string($closure)) {
            self::call($closure);
            return;
        }

        $controller = Factory::controller();
        $closure = $closure->bindTo($controller, $controller);
        $closure();
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
        return Str::interpolate(self::PATH_FORMAT, [
            'app' => getAppDirectory(),
            'dir' => $dir
        ]);
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
     * @param  string  $dir  the directory of the controller
     *
     * @return boolean true if the controller's method exists, false otherwise
     */
    function methodExists(string $dir)
    {
        $controller = Str::before($dir, self::METHOD_SEPARATOR);
        $method = Str::after($dir, self::METHOD_SEPARATOR);
        $class = self::NAMESPACE . Str::pathToNamespace($controller);

        if (!class_exists($class)) {
            return false;
        }

        $class = new \ReflectionClass($class);

        if (!$class->hasMethod($method)) {
            return false;
        }

        return true;
    }


    /**
     * Set the utilities
     *
     * @param  array  $utilities  the utilities array
     */
    public function setUtilities(array $utilities)
    {
        if (!is_array($utilities)) {
            return;
        }

        foreach($utilities as $key => $class) {
            $this->$key = Factory::utility($class);
        }
    }

}
