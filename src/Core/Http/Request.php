<?php

namespace Wolff\Core\Http;

class Request
{

    /**
     * List of parameters
     *
     * @var array
     */
    private $params;

    /**
     * List of body parameters
     *
     * @var array
     */
    private $body;

    /**
     * List of files
     *
     * @var array
     */
    private $files;

    /**
     * Current server superglobal
     *
     * @var array
     */
    private $server;


    /**
     * Default constructor
     *
     * @param  array  $params  the url parameters
     * @param  array  $body  the body parameters
     * @param  array  $files  the files
     * @param  array  $server  the superglobal server
     */
    public function __construct(array $params,
                                array $body,
                                array $files,
                                array $server)
    {
        $this->params = $_GET;
        $this->body = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->headers = $this->parseHeaders($_SERVER);
    }


    /**
     * Returns the headers parsed
     *
     * @param  array  $server  the superglobal server array
     *
     * @return array The headers parsed
     */
    private function parseHeaders(array $server)
    {
        $headers = [];

        foreach ($server as $header => $val) {
            if (substr($header, 0, 5) !== 'HTTP_') {
                continue;
            }

            $key = ucwords(str_replace('_', '-', strtolower(substr($header, 5))), '-');
            $headers[$key] = $val;
        }

        return $headers;
    }


    /**
     * Returns the specified parameter.
     * The key parameter accepts dot notation
     *
     * @param  string  $key  the parameter key
     *
     * @return mixed The specified parameter.
     */
    public function param(string $key = null)
    {
        if (!isset($key)) {
            return $this->params;
        }

        return val($this->params, $key);
    }


    /**
     * Returns true if the specified parameter is set,
     * false otherwise.
     *
     * @param  string  $key  the parameter key
     *
     * @return bool True if the specified parameter is set,
     * false otherwise.
     */
    public function hasParam(string $key)
    {
        return val($this->params, $key) !== null;
    }


    /**
     * Returns the specified body parameter.
     * The key parameter accepts dot notation
     *
     * @param  string  $key  the body parameter key
     *
     * @return mixed The specified body parameter.
     */
    public function body(string $key = null)
    {
        if (!isset($key)) {
            return $this->body;
        }

        return val($this->body, $key);
    }


    /**
     * Returns true if the specified body parameter is set,
     * false otherwise.
     *
     * @param  string  $key  the parameter key
     *
     * @return bool True if the specified body parameter is set,
     * false otherwise.
     */
    public function has(string $key)
    {
        return val($this->body, $key) !== null;
    }


    /**
     * Returns the specified file.
     *
     * @param  string  $key  the file key
     *
     * @return mixed The specified file.
     */
    public function file(string $key = null)
    {
        if (!isset($key)) {
            return $this->files;
        }

        return $this->files[$key];
    }


    /**
     * Returns true if the specified file is set,
     * false otherwise.
     *
     * @param  string  $key  the parameter key
     *
     * @return bool True if the specified file is set,
     * false otherwise.
     */
    public function hasFile(string $key)
    {
        return array_key_exists($key, $this->files);
    }


    /**
     * Returns the headers array,
     * or the specified header key
     *
     * @param  string  $key  the header key to get
     *
     * @return mixed The headers array,
     * or the specified header key
     */
    public function getHeader(string $key = null)
    {
        if (!isset($key)) {
            return $this->headers;
        }

        return $this->headers[$key];
    }


    /**
     * Returns the request method
     *
     * @return string The request method
     */
    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }


    /**
     * Returns the request uri
     *
     * @return string The request uri
     */
    public function getUrl()
    {
        return $this->server['REQUEST_URI'];
    }

}
