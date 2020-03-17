<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Language
{

    const BAD_FILE_ERROR = 'The %s language file for \'%s\' doesn\'t return an associative array';
    const PATH_FORMAT = '%s/' . CORE_CONFIG['languages_dir'] . '/%s/%s.php';


    /**
     * Returns the content of a language, or false if
     * it doesn't exists
     *
     * @param  string  $dir  the language directory
     * @param  string  $language  the language selected
     *
     * @return mixed the content of a language, or false if
     * it doesn't exists
     */
    public static function get(string $dir, string $language = CONFIG['language'])
    {
        if (Str::contains($dir, '.')) {
            $key = Str::after($dir, '.');
            $dir = Str::before($dir, '.');
        }

        $data = [];

        if (self::exists($dir, $language)) {
            $data = (include self::getPath($dir, $language));
        } else {
            return false;
        }

        if (!is_array($data)) {
            throw new \Error(sprintf(self::BAD_FILE_ERROR, $language, $dir));
        }

        if (isset($key)) {
            return $data[$key];
        }

        return $data;
    }


    /**
     * Returns the path of a language file
     *
     * @param  string  $dir  the language directory
     * @param  string  $language  the language selected
     *
     * @return string the path of a language file
     */
    private static function getPath(string $dir, string $language)
    {
        return sprintf(self::PATH_FORMAT, CONFIG['app_dir'], $language, $dir);
    }


    /**
     * Returns true if the specified language exists,
     * false otherwise
     *
     * @param  string  $dir  the language directory
     * @param  string  $language  the language selected
     *
     * @return string true if the specified language exists,
     * false otherwise
     */
    public static function exists(string $dir, string $language = CONFIG['language'])
    {
        $file_path = self::getPath($dir, $language);

        return file_exists($file_path);
    }
}
