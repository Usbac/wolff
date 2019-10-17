<?php

namespace Core;

use Utilities\Str;

class View
{
    const FILE_FORMAT = '.wlf';

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

        Template::get($dir, $data, $cache);
    }


    /**
     * Get a view content
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data
     *
     * @return mixed the view or false in case of errors
     */
    public static function get(string $dir, array $data = [])
    {
        $dir = Str::sanitizePath($dir);

        if (!self::log($dir)) {
            return false;
        }

        return Template::getView($dir, $data);
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

        return Template::render($dir, $data, $cache);
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
        $path = getAppDirectory() . CORE_CONFIG['views_folder'] . '/' . $view . self::FILE_FORMAT;

        if (!file_exists($path)) {
            Log::error("View '$view' doesn't exists");

            return false;
        }

        return true;
    }

}