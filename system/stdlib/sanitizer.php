<?php

namespace {


    /**
     * Sanitize an url
     *
     * @param  string url the url
     *
     * @return string the url sanitized
     */
    function sanitizeURL(string $url)
    {
        return filter_var(rtrim(strtolower($url), '/'), FILTER_SANITIZE_URL);
    }


    /**
     * Sanitize an email
     *
     * @param  string email the email
     *
     * @return string the email sanitized
     */
    function sanitizeEmail(string $email)
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }


    /**
     * Sanitize an string to integer (only numbers and +-)
     *
     * @param  string int the integer
     *
     * @return string the integer sanitized
     */
    function sanitizeInt(string $int)
    {
        return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
    }


    /**
     * Sanitize an string to float (only numbers, fractions and +-)
     *
     * @param  string float the float
     *
     * @return string the float sanitized
     */
    function sanitizeFloat(string $float)
    {
        return filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }


    /**
     * Sanitize a path for only letters, numbers and slashes
     *
     * @param  string path the path
     *
     * @return string the path sanitized
     */
    function sanitizePath(string $path)
    {
        return preg_replace('/[^a-zA-Z0-9_\-\/. ]/', '', $path);
    }
}