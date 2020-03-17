<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class View
{

    const PATH_FORMAT = '%s/' . CORE_CONFIG['views_dir'] . '/%s.' . CORE_CONFIG['views_format'];


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

        if (!self::exists($dir)) {
            throw new \Error("View '$dir' doesn't exists");
        }

        echo Template::getRender($dir, $data, $cache);
    }


    /**
     * Returns the original view content
     *
     * @param  string  $view  the view directory
     *
     * @return mixed the original view content
     */
    public static function getSource(string $view)
    {
        $view = Str::sanitizePath($view);

        if (!self::exists($view)) {
            throw new \Error("View '$view' doesn't exists");
        }

        return file_get_contents(self::getPath($view));
    }


    /**
     * Returns the view content with the template format applied
     * over it
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data
     * @param  bool  $cache  use or not the cache system
     *
     * @return mixed the view content with the template format applied
     * over it
     */
    public static function get(string $dir, array $data = [], bool $cache = true)
    {
        $dir = Str::sanitizePath($dir);

        if (!self::exists($view)) {
            throw new \Error("View '$view' doesn't exists");
        }

        return Template::get($dir, $data, $cache);
    }


    /**
     * Returns a view content rendered
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data
     * @param  bool  $cache  use or not the cache system
     *
     * @return mixed the view rendered or false in case of errors
     */
    public static function getRender(string $dir, array $data = [], bool $cache = true)
    {
        $dir = Str::sanitizePath($dir);

        if (!self::exists($dir)) {
            throw new \Error("View '$dir' doesn't exists");
        }

        return Template::getRender($dir, $data, $cache);
    }


    /**
     * Returns a view file path
     *
     * @param  string  $path  the view directory
     *
     * @return string the view file path
     */
    public static function getPath(string $path)
    {
        return sprintf(self::PATH_FORMAT, CONFIG['app_dir'], $path);
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

}