<?php

namespace Wolff\Core\Http;

class Response
{

    /**
     * The HTTP status code.
     *
     * @var int
     */
    private $status_code;

    /**
     * The header location.
     *
     * @var string
     */
    private $url;

    /**
     * The header tag list.
     *
     * @var array
     */
    private $headers;


    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->status_code = null;
        $this->headers = [];
        $this->url = '';
    }


    /**
     * Returns the HTTP headers
     *
     * @return array the HTTP headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * Returns the HTTP status code
     *
     * @return int the HTTP status code
     */
    public function getCode()
    {
        return $this->status_code;
    }


    /**
     * Returns the header location
     *
     * @return string the header location
     */
    public function getRedirect()
    {
        return $this->url;
    }


    /**
     * Sets the value of an existent or new header
     *
     * @param  string  $key  the header key
     * @param  string  $value  the header value
     *
     * @return Response this
     */
    public function header(string $key, string $value)
    {
        $this->headers[trim($key)] = $value;

        return $this;
    }


    /**
     * Removes an existent header
     *
     * @param  string  $key  the header key
     *
     * @return Response this
     */
    public function remove(string $key)
    {
        if (key_exists($key, $this->headers)) {
            unset($this->headers[$key]);
        }

        return $this;
    }


    /**
     * Sets the HTTP status code
     *
     * @param  int  $status  the HTTP status code
     *
     * @return Response this
     */
    public function setCode(int $status = null)
    {
        $this->status_code = $status;

        return $this;
    }


    /**
     * Sets the header location and HTTP status code
     *
     * @param  string  $url  the header location
     * @param  int  $status  the HTTP status code
     *
     * @return Response this
     */
    public function redirect(string $url, int $status = null)
    {
        if (isset($status)) {
            $this->setCode($status);
        }

        $this->url = $url;

        return $this;
    }


    /**
     * Executes the response with the available values
     */
    public function go()
    {
        foreach ($this->headers as $key => $header) {
            header("$key: $header");
        }

        redirect($this->url, $this->status_code);
    }
}
