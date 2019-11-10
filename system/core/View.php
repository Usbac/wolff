<?php

namespace Core;

use Utilities\Str;

class View
{

    const PATH_FORMAT = '{app}' . CORE_CONFIG['views_folder'] . '/{dir}' . CORE_CONFIG['views_format'];


    /**
     * Load a view
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the view data
     * @param  bool  $cache  use or not the cache system
     */
    public static function render(string $dir, array $data = [], bool $cache = true)
    {
        $dir = Str::sanitizePath($dir);

        if (!self::log($dir)) {
            return;
        }

        Template::render($dir, $data, $cache);
    }


    /**
     * Returns the original view content or false in case of errors
     *
     * @param  string  $dir  the view directory
     *
     * @return mixed the original view content or false in case of errors
     */
    public static function getSource(string $view)
    {
        $view = Str::sanitizePath($view);

        if (!self::log($view)) {
            return false;
        }

        return file_get_contents(self::getPath($view));
    }


    /**
     * Returns the view content with the template format applied
     * over it, or false in case of errors
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data
     *
     * @return mixed the view content with the template format applied
     * over it, or false in case of errors
     */
    public static function get(string $dir, array $data = [])
    {
        $dir = Str::sanitizePath($dir);

        if (!self::log($dir)) {
            return false;
        }

        return Template::get($dir, $data);
    }


    /**
     * Returns a view content rendered
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data
     *
     * @return mixed the view rendered or false in case of errors
     */
    public static function getRender(string $dir, array $data = [], bool $cache = false)
    {
        $dir = Str::sanitizePath($dir);

        if (!self::log($dir)) {
            return false;
        }

        return Template::getRender($dir, $data, $cache);
    }


    /**
     * Returns a view file path
     *
     * @param  string  $dir  the view directory
     *
     * @return string the view file path
     */
    public static function getPath(string $path)
    {
        return Str::interpolate(self::PATH_FORMAT, [
            'app' => getAppDir(),
            'dir' => $path
        ]);
    }


    /**
     * Returns true if the view exists in the indicated directory,
     * false otherwise
     *
     * @param  string  $dir  the directory of the view
     *
     * @return boolean true if the view exists, false otherwise
     */
    public static function exists(string $dir)
    {
        return file_exists(self::getPath($dir));
    }


    /**
     * Returns true if the view file exists, false otherwise.
     * Warning: This functions logs an error message.
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data
     *
     * @return  bool true if the view file exists, false otherwise.
     */
    private static function log(string $view)
    {
        if (!self::exists($view)) {
            Log::error("View '$view' doesn't exists");

            return false;
        }

        return true;
    }

}
