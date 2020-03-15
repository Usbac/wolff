<?php

namespace {

    if (!function_exists('config')) {

        /**
         * Returns the given key of the CONFIG array or null
         * if it does not exists.
         * The key must be in dot syntax. Like 'user.name'.
         *
         * @param  string  $key  the CONFIG array key
         *
         * @return mixed the given key of the CONFIG array or null
         * if it does not exists.
         */
        function config(string $key)
        {
            $keys = explode('.', $key);
            $arr = CONFIG;

            foreach($keys as $key) {
                if (!is_array($arr) || !array_key_exists($key, $arr)) {
                    return null;
                }

                $arr = &$arr[$key];
            }

            return $arr;
        }
    }

    if (!function_exists('getPublic')) {

        /**
         * Returns the public directory of the project
         * relative to the server root
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the public directory of the project
         */
        function getPublic(string $path = '')
        {
            return substr(CONFIG['public_dir'], strlen($_SERVER['DOCUMENT_ROOT'])) . '/' . $path;
        }
    }

    if (!function_exists('wolffVersion')) {

        /**
         * Returns the current version of Wolff
         * @return string the current version of Wolff
         */
        function wolffVersion()
        {
            return CORE_CONFIG['version'];
        }
    }

}
