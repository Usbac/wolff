<?php

namespace Core;

use Utilities\Str;

class Language
{
    const BAD_FILE_ERROR = 'The {language} language file for \'{dir}\' doesn\'t return an associative array';
    const EXISTS_ERROR = 'The {language} language for \'{dir}\' doesn\'t exists';
    const EMPTY_WARNING = 'The {language} language content for \'{dir}\' is empty';
    const KEY_WARNING = 'The \'{key}\' key doesn\'t exists in the {language} language array';
    const PATH_FORMAT = '{app}' . CORE_CONFIG['languages_dir'] . '/{language}/{dir}.php';


    /**
     * Returns the content of a language, or false in case
     * of errors
     *
     * @param  string  $dir  the language directory
     * @param  string  $language  the language selected
     *
     * @return mixed the content of a language, or false in case
     * of errors
     */
    public static function get(string $dir, string $language = null)
    {
        $language = $language ?? getLanguage();
        $dir = Str::sanitizePath($dir);
        if (Str::contains($dir, '.')) {
            $key = Str::after($dir, '.');
            $dir = Str::before($dir, '.');
        }

        $file_path = self::getPath($dir, $language);
        $data = [];

        if (file_exists($file_path)) {
            $data = (include $file_path);
        } else {
            Log::error(Str::interpolate(self::EXISTS_ERROR, [
                'language' => $language,
                'dir'      => $dir
            ]));

            return false;
        }

        if (!is_array($data)) {
            Log::error(Str::interpolate(self::BAD_FILE_ERROR, [
                'language' => $language,
                'dir'      => $dir
            ]));

            return false;
        }

        if (empty($data)) {
            Log::warning(Str::interpolate(self::EMPTY_WARNING, [
                'language' => $language,
                'dir'      => $dir
            ]));

            return false;
        }

        if (isset($key)) {
            if (!array_key_exists($key, $data)) {
                Log::warning(Str::interpolate(self::KEY_WARNING, [
                    'key'      => $key,
                    'language' => $language
                ]));

                return false;
            }

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
        return Str::interpolate(self::PATH_FORMAT, [
            'app'       => getAppDir(),
            'language'  => $language,
            'dir'       => $dir
        ]);
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
        $file_path = self::getPath($language, Str::sanitizePath($dir));

        return file_exists($file_path);
    }
}
