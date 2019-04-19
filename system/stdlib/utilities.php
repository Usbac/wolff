<?php

namespace {

    
    /**
     * Checks if the substring is present in another string
     * @param string $str the string
     * @param string $needle substring you are looking for
     * @return boolean true if the substring is present in the string, false otherwise
     */
    function strContains(string $str, string $needle) {
        return strpos($str, $needle) !== false;
    }


    /**
     * Print a string and then die
     * @param $str the string to print
     */
    function echod($str) {
        echo $str;
        die();
    }


    /**
     * Print an array in a nice looking way
     * @param array $array the array to print
     */
    function printr(array $array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }


    /**
     * Print an array in a nice looking way and then die
     * @param array $array the array to print
     */
    function printrd(array $array) {
        printr($array);
        die();
    }


    /**
     * Var dump a variable and then die
     * @param $var the variable
     */
    function dumpd($var) {
        var_dump($var);
        die();
    }


    /**
     * Returns the first element of an array, or false if it's empty
     * @param array $array
     * @return mixed The first element of an array, or false if it's empty
     */
    function arrayFirst($array) {
        return array_values($array)[0] ?? false;
    }


    /**
     * Convert an array content into a csv file and download it
     * @param string $filename the desired filename without extension
     * @param array $array the array
     */
    function arrayToCsv(string $filename, array $array) {
        $filename .= ".csv";
        $file = fopen($filename, 'w');

        //Single array
        if (count($array) == count($array, COUNT_RECURSIVE)) {
            fputcsv($file, array_keys($array));
            fputcsv($file, $array);
            //Multidimensional array
        } else {
            fputcsv($file, array_keys(arrayFirst($array)));
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


    /**
     * Returns the current client IP
     * @return string the current client IP
     */
    function getClientIP() {
        $http_client_ip = filter_var($_SERVER['HTTP_CLIENT_IP'] ?? "", FILTER_VALIDATE_IP);
        $http_forwarded = filter_var($_SERVER['HTTP_X_FORWARDED_FOR'] ?? "", FILTER_VALIDATE_IP);

        if (!empty($http_client_ip)) {
            return $http_client_ip;
        }

        if (!empty($http_forwarded)) {
            return $http_forwarded;
        }

        return $_SERVER['REMOTE_ADDR'];
    }


    /**
     * Returns the server root directory
     * @return string the server root directory
     */
    function getServerRoot() {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    
    /**
     * Returns the current page relative to the project url
     * @return string the current page relative to the project url
     */
    function getCurrentPage() {
        return substr($_SERVER['REQUEST_URI'], strlen(getDirectory()));
    }


    /**
     * Returns the time between the page load start and the current time
     * @return float the time between the page load start and the current time
     */
    function getBenchmark() {
        return microtime(true) - WOLFF_START;
    }
}