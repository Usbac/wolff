<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Middleware
{

    /**
     * List of middlewares
     * of type before
     *
     * @var array
     */
    private static $middlewares_before = [];

    /**
     * List of middlewares
     * of type after
     *
     * @var array
     */
    private static $middlewares_after = [];

    /**
     * Continue or not to the
     * next middleware
     *
     * @var bool
     */
    private static $next = false;


    /**
     * Loads all the middlewares files that matches the current route
     *
     * @param  string  $type  the type of middlewares to load
     * @param  string  $url  the url to match the middlewares
     * @param  Http\Request  $req  the request object
     */
    private static function load(string $type, string $url, Http\Request $req)
    {
        if (empty(self::${'middlewares_' . $type})) {
            return;
        }

        $middlewares = self::${'middlewares_' . $type};
        self::$next = false;

        $url = explode('/', $url);
        $url_length = count($url) - 1;
        foreach ($middlewares as $key => $val) {
            $middleware = explode('/', $val['url']);
            $middleware_length = count($val['url']) - 1;

            for ($i = 0; $i <= $middleware_length && $i <= $url_length; $i++) {
                if ($url[$i] !== $middleware[$i] && $middleware[$i] !== '*') {
                    break;
                }

                if ($middleware[$i] === '*' ||
                    ($i === $url_length && $i === $middleware_length)) {
                    $val['function']($req, function() {
                        self::$next = true;
                    });
                    break;
                }
            }

            if (!self::$next) {
                break;
            }
        }
    }


    /**
     * Loads the middlewares files of type before that matches the current route
     *
     * @param  string  $url  the url to match the middlewares
     * @param  Http\Request  $req  the request object
     */
    public static function loadBefore(string $url, Http\Request $req)
    {
        self::load('before', $url, $req);
    }


    /**
     * Loads the middlewares files of type after that matches the current route
     *
     * @param  string  $url  the url to match the middlewares
     * @param  Http\Request  $req  the request object
     */
    public static function loadAfter(string $url, Http\Request $req)
    {
        self::load('after', $url, $req);
    }


    /**
     * Proxy to call the middlewares of type
     * before and after
     *
     * @param  string  $type  the type of middlewares to load
     * @param  array  $args  the arguments
     */
    public static function __callStatic(string $type, $args)
    {
        if (!in_array($type, [ 'before', 'after' ])) {
            return;
        }

        array_push(self::${'middlewares_' . $type}, [
            'url'      => Str::sanitizeURL($args[0]),
            'function' => $args[1]
        ]);
    }

}
