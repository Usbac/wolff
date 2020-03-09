<?php

namespace {

    if (!function_exists('isAssoc')) {

        /**
         * Returns true if the given array is
         * associative (numbers as keys), false otherwise.
         *
         * @param  array  $arr  the array
         *
         * @return bool true if the given array is associative,
         * false otherwise
         */
        function isAssoc(array $arr)
        {
            return (array_keys($arr) !== range(0, count($arr) -1));
        }
    }

    if (!function_exists('val')) {

        /**
         * Returns the key value of the
         * given array, or null if it doesn't exists.
         *
         * The key param can use the dot notation.
         *
         * @param  array  $arr  the array
         * @param  string  $key  the array key to obtain
         *
         * @return mixed the value of the specified key in the array
         */
        function val(array $arr, string $key = null)
        {
            $keys = explode('.', $key);

            if (is_null($key)) {
                return $arr;
            }

            foreach($keys as $key) {
                if (!is_array($arr) || !array_key_exists($key, $arr)) {
                    return null;
                }

                $arr = &$arr[$key];
            }

            return $arr;
        }
    }

    if (!function_exists('echod')) {

        /**
         * Print a string and die
         */
        function echod()
        {
            $args = func_get_args();
            foreach ($args as $arg) {
                echo $arg;
            }

            die();
        }
    }

    if (!function_exists('printr')) {

        /**
         * Print the given arrays in a nice looking way
         */
        function printr()
        {
            $args = func_get_args();

            echo "<pre>";
            foreach ($args as $arg) {
                print_r($arg);
            }
            echo "</pre>";
        }
    }

    if (!function_exists('printrd')) {

        /**
         * Print the given arrays in a nice looking way and die
         */
        function printrd()
        {
            $args = func_get_args();

            echo "<pre>";
            foreach ($args as $arg) {
                print_r($arg);
            }
            echo "</pre>";

            die();
        }
    }

    if (!function_exists('dumpd')) {

        /**
         * Var dump a variable and die
         */
        function dumpd()
        {
            $args = func_get_args();
            foreach ($args as $arg) {
                var_dump($arg);
            }

            die();
        }
    }

    if (!function_exists('dumpAll')) {

        /**
         * Var dump all the variables
         */
        function dumpAll()
        {
            $all = print_r(var_dump($GLOBALS), 1);

            echo '<pre>';
            echo htmlspecialchars($all);
            echo '</pre>';
        }
    }

    if (!function_exists('redirect')) {

        /**
         * Make a redirection
         *
         * @param  string  $url  the url to redirect to
         * @param  int  $status  the HTTP status code
         */
        function redirect(string $url = null, int $status = null)
        {
            //Set url to the homepage when null
            if (!isset($url)) {
                $http = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://';
                $directory = str_replace('\\', '/', getProjectDir());

                if (substr($directory, -1) != '/' && substr($url, 0, 1) != '/') {
                    $directory .= '/';
                }

                $url = $http . $_SERVER['HTTP_HOST'] . $directory;
            }

            header("Location: $url", true, $status);
            exit;
        }
    }

    if (!function_exists('isJson')) {

        /**
         * Returns true if the given string is a Json, false otherwise
         *
         * @param  string  $str  the string
         *
         * @return string true if the given string is a Json, false otherwise
         */
        function isJson(string $str)
        {
            $str = json_decode($str);

            return json_last_error() === JSON_ERROR_NONE;
        }
    }

    if (!function_exists('toArray')) {

        /**
         * Returns the given variable as an associative array
         *
         * @param  string  $obj  the object
         *
         * @return string the given variable as an associative array
         */
        function toArray($obj)
        {
            //Json
            if (is_string($obj)) {
                $json = json_decode($obj);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $obj = json_decode($obj);
                }
            }

            $new = [];

            //Object
            if (is_object($obj)) {
                $obj = (array) $obj;
            }

            //Array
            if (is_array($obj)) {
                foreach($obj as $key => $val) {
                    $new[$key] = toArray($val);
                }
            } else {
                $new = $obj;
            }

            return $new;
        }
    }

    if (!function_exists('url')) {

        /**
         * Returns the complete url relative to the local site
         *
         * @param  string  $url  the url to redirect to
         *
         * @return string the complete url relative to the local site
         */
        function url(string $url = '')
        {
            $http = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://';
            $directory = str_replace('\\', '/', getProjectDir());

            if (substr($directory, -1) != '/' && substr($url, 0, 1) != '/') {
                $directory .= '/';
            }

            return $http . $_SERVER['HTTP_HOST'] . $directory . $url;
        }
    }

    if (!function_exists('local')) {

        /**
         * Returns true if the current script is running in localhost,
         * false otherwise
         *
         * @return bool true if the current script is running in localhost,
         * false otherwise
         */
        function local()
        {
            return in_array($_SERVER['REMOTE_ADDR'] ?? '::1', [ '127.0.0.1', '::1' ]);
        }
    }

    if (!function_exists('average')) {

        /**
         * Returns the average value of the given array
         *
         * @param  array  $arr  the array with the numeric values
         * @return float|int the average value of the given array
         */
        function average(array $arr)
        {
            return array_sum($arr) / count($arr);
        }
    }

    if (!function_exists('arrayToCsv')) {

        /**
         * Convert an array content into a csv file and download it
         *
         * @param  string  $filename  the desired filename without extension
         * @param  array  $array  the array
         * @param  bool  $printKeys  print the array keys or not
         */
        function arrayToCsv(string $filename, array $array, bool $printKeys = true)
        {
            $filename .= ".csv";
            $file = fopen($filename, 'w');

            //Single array
            if (count($array) === count($array, COUNT_RECURSIVE)) {
                if ($printKeys) {
                    fputcsv($file, array_keys($array));
                }

                fputcsv($file, $array);
            //Multidimensional array
            } else {
                if ($printKeys) {
                    fputcsv($file, array_keys($array[0]));
                }

                foreach ($array as $row) {
                    fputcsv($file, $row);
                }
            }

            fclose($file);

            header('Content-Description: File Transfer');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . basename($filename));
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
        }
    }

    if (!function_exists('getClientIP')) {

        /**
         * Returns the current client IP
         * @return string the current client IP
         */
        function getClientIP()
        {
            $http_client_ip = filter_var($_SERVER['HTTP_CLIENT_IP'] ?? '', FILTER_VALIDATE_IP);
            $http_forwarded = filter_var($_SERVER['HTTP_X_FORWARDED_FOR'] ?? '', FILTER_VALIDATE_IP);

            if (!empty($http_client_ip)) {
                return $http_client_ip;
            }

            if (!empty($http_forwarded)) {
                return $http_forwarded;
            }

            return $_SERVER['REMOTE_ADDR'] ?? '';
        }
    }

    if (!function_exists('getUserAgent')) {

        /**
         * Returns the HTTP user agent
         * @return string the HTTP user agent
         */
        function getUserAgent()
        {
            return $_SERVER['HTTP_USER_AGENT'];
        }
    }

    if (!function_exists('getServerRoot')) {

        /**
         * Returns the server root directory
         * @return string the server root directory
         */
        function getServerRoot()
        {
            return $_SERVER['DOCUMENT_ROOT'];
        }
    }

    if (!function_exists('getCurrentPage')) {

        /**
         * Returns the current page relative to the project url
         * @return string the current page relative to the project url
         */
        function getCurrentPage()
        {
            return substr($_SERVER['REQUEST_URI'], strlen(getProjectDir()));
        }
    }

    if (!function_exists('getPureCurrentPage')) {

        /**
         * Returns the current page without arguments
         * @return string the current page without arguments
         */
        function getPureCurrentPage()
        {
            $host = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

            if (strpos($_SERVER['REQUEST_URI'], '?') === false) {
                return $host . $_SERVER['REQUEST_URI'];
            }

            $page = explode('?', $_SERVER['REQUEST_URI']);
            return $host . $page[0];
        }
    }

    if (!function_exists('getBenchmark')) {

        /**
         * Returns the time between the page load start and the current time
         * @return float the time between the page load start and the current time
         */
        function getBenchmark()
        {
            return microtime(true) - CORE_CONFIG['start'];
        }
    }

    if (!function_exists('inCli')) {

        /**
         * Returns true if running from command line interface, false otherwise
         * @return bool true if running from command line interface, false otherwise
         */
        function inCli()
        {
            return (php_sapi_name() === 'cli');
        }
    }

    if (!function_exists('deleteFilesInDir')) {

        /**
         * Delete all the files in the given directory
         *
         * @param  string  $dir  the directory path
         */
        function deleteFilesInDir($dir)
        {
            $files = glob($dir . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }

    if (!function_exists('isInt')) {

        /**
         * Returns true if the given variable
         * complies with an int, false otherwise
         *
         * @param  mixed  $int  the variable
         */
        function isInt($int) {
            return filter_var($int, FILTER_VALIDATE_INT) !== false;
        }
    }

    if (!function_exists('isFloat')) {

        /**
         * Returns true if the given variable
         * complies with an float, false otherwise
         *
         * @param  mixed  $float  the variable
         */
        function isFloat($float) {
            return filter_var($float, FILTER_VALIDATE_FLOAT) !== false;
        }
    }

    if (!function_exists('isBool')) {

        /**
         * Returns true if the given variable complies with an boolean,
         * false otherwise
         * Only the numeric values 1 and 0, and the strings
         * 'true', 'false', '1' and '0' are counted as boolean.
         *
         * @param  mixed  $bool  the variable
         */
        function isBool($bool) {
            return in_array(strval($bool), ['true', 'false', '1', '0']);
        }
    }

}
