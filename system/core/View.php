<?php

namespace Core;

use Utilities\Str;

class View
{

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

        return file_get_contents(getAppDirectory() . CORE_CONFIG['views_folder'] . '/' . $view . CORE_CONFIG['views_format']);
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
     * Get a view content rendered
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
        $path = getAppDirectory() . CORE_CONFIG['views_folder'] . '/' . $view . CORE_CONFIG['views_format'];

        if (!file_exists($path)) {
            Log::error("View '$view' doesn't exists");

            return false;
        }

        return true;
    }

}
