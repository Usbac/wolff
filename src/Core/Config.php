<?php

namespace Wolff\Core;

class Config
{

    /**
     * List of configuration variables
     * @var array
     */
    private static $data = [];


    /**
     * Path of the environment file
     * @var string
     */
    private static $env_path = CONFIG['root_dir'] . '.env';


    /**
     * Initializes the configuration data
     */
    public static function init()
    {
        if (is_string(CONFIG['env_file'] ?? null)) {
            self::$env_path = CONFIG['root_dir'] . CONFIG['env_file'];
        }

        self::$data = CONFIG;
        self::mapEnv();

        if (CONFIG['env_override'] ?? false) {
            array_merge(self::$data, $_ENV);
        }
    }


    /**
     * Returns the configuration
     */
    public static function get(string $key = null)
    {
        if (!isset($key)) {
            return self::$data;
        }

        return self::$data[$key];
    }


    /**
     * Maps the environment variables from an existing env file.
     * This is Wolff's own parser, an existing one has not been used
     * because lol
     */
    public static function mapEnv()
    {
        if (($content = \file_get_contents(self::$env_path)) === false) {
            return [];
        }

        $lines = explode(PHP_EOL, $content);

        foreach ($lines as $line) {
            if (!($index_equal = strpos($line, '='))) {
                continue;
            }

            $key = trim(substr($line, 0, $index_equal));
            $val = trim(substr($line, $index_equal + 1));
            // Anything between or not single/double quotes, excluding the hashtag character after it
            $val = preg_match("/'(.*)'|\"(.*)\"|(^[^#]+)/", $val, $matches);
            $val = trim($matches[0] ?? '', '\'" ');

            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }

}
