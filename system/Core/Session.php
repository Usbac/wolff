<?php

namespace Core;

class Session
{

    const DATE_FORMAT = 'Y-m-d H:i:s';


    /**
     * Destroy and unset the session if the live time is zero or less
     */
    public function __construct()
    {
        $_SESSION['flash_data'] = [];

        if ($this->hasExpired()) {
            $this->empty();
            $this->kill();

            return;
        }

        if (!$this->isValid()) {
            $this->initialize();
        }

        $this->unsetExpiredVariables();
    }


    /**
     * Initialize all the session variables
     */
    private function initialize()
    {
        $this->empty();

        $_SESSION['IPaddress'] = getClientIP();
        $_SESSION['userAgent'] = getUserAgent();
        $_SESSION['start_time'] = microtime(true);
        $_SESSION['vars_tmp_time'] = [];
    }


    /**
     * Unset all the session variables that have expired
     */
    private function unsetExpiredVariables()
    {
        foreach ($_SESSION['vars_tmp_time'] as $key => $value) {
            if (time() >= $value) {
                $this->delete($key);
            }
        }
    }

    
    /**
     * Start the session
     */
    public function start()
    {
        session_start();
    }


    /**
     * Returns true if the current session has expired, false otherwise
     * @return bool true if the current session has expired, false otherwise
     */
    public function hasExpired()
    {
        return isset($_SESSION['end_time']) && time() >= $_SESSION['end_time'];
    }


    /**
     * Returns true if the current IP and the userAgent are the same
     * than the IP and userAgent of the previous connection.
     * This is done for preventing session hijacking.
     * @return bool true if the current IP address and the userAgent are the same
     * than the IP address and userAgent of the previous connection.
     */
    private function isValid()
    {
        if (!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent'])) {
            return false;
        }

        if ($_SESSION['IPaddress'] != getClientIP()) {
            return false;
        }

        if ($_SESSION['userAgent'] != getUserAgent()) {
            return false;
        }

        return true;
    }


    /**
     * Get a session variable
     *
     * @param  string  $key  the variable key
     *
     * @return object the variable
     */
    public function get(string $key = null)
    {
        if (!isset($key)) {
            return $_SESSION;
        }

        return $_SESSION[$key];
    }


    /**
     * Set a session variable
     *
     * @param  string  $key  the variable key
     * @param  string value the variable value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }


    /**
     * Returns true if the session variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     * 
     * @return bool true if the session variable exists, false otherwise
     */
    public function has(string $key) 
    {
        return array_key_exists($key, $_SESSION);
    }


    /**
     * Returns the numbers of elements in the session
     * 
     * @return int the numbers of elements in the session
     */
    public function count() 
    {
        return count($_SESSION);
    }


    /**
     * Add time to the session live time (in minutes)
     *
     * @param  int  $time  the session live time to add
     */
    public function addTime(int $time)
    {
        $_SESSION['end_time'] += $time * 60;
    }


    /**
     * Get a live time (in minutes) of a session variable
     *
     * @param  string  $key  the variable key
     * @param  bool  $gmdate  return the time in date format
     *
     * @return int the variable live time
     */
    public function getVarTime(string $key, bool $gmdate = false)
    {
        $remaining = 0;
        if (isset($_SESSION['vars_tmp_time'][$key])) {
            $remaining = $_SESSION['vars_tmp_time'][$key] - time();
        }

        if ($gmdate) {
            return gmdate(self::DATE_FORMAT, ($remaining > 0) ? $remaining : 0);
        }

        return ($remaining > 0) ? $remaining : 0;
    }


    /**
     * Set a live time (in minutes) to a session variable
     *
     * @param  string  $key  the variable key
     * @param  int  $time  the variable live time
     */
    public function setVarTime(string $key, int $time = 1)
    {
        $_SESSION['vars_tmp_time'][$key] = time() + ($time * 60);
    }


    /**
     * Add more live time (in minutes) to a session variable
     *
     * @param  string  $key  the variable key
     * @param  int  $time  the variable time to add
     */
    public function addVarTime(string $key, int $time = 1)
    {
        $_SESSION['vars_tmp_time'][$key] += ($time * 60);
    }


    /**
     * Delete a session variable
     *
     * @param  string  $key  the variable key
     */
    public function delete(string $key)
    {
        unset($_SESSION[$key]);
    }


    /**
     * Set the session live time (in minutes) starting from
     * the moment this function is called
     *
     * @param  int  $time  the time
     */
    public function setTime(int $time)
    {
        $_SESSION['live_time'] = ($time * 60);
        $_SESSION['end_time'] = time() + ($time * 60);
    }


    /**
     * Returns the session creation time
     *
     * @param  bool  $gmdate  format the time in H:i:s
     *
     * @return mixed the session creation time
     */
    public function getTime(bool $gmdate = false)
    {
        if ($gmdate) {
            return gmdate(self::DATE_FORMAT, $_SESSION['start_time']);
        }

        return $_SESSION['start_time'];
    }


    /**
     * Returns the time since the session was created in seconds
     * @return mixed the time since the session was created in seconds
     */
    public function getPassedTime()
    {
        return microtime(true) - $_SESSION['start_time'];
    }


    /**
     * Returns the established session live time (in minutes)
     *
     * @param  bool  $gmdate  format the time in H:i:s
     *
     * @return mixed the established session live time (in minutes)
     */
    public function getLiveTime(bool $gmdate = false)
    {
        if ($gmdate) {
            return gmdate(self::DATE_FORMAT, $_SESSION['live_time']);
        }

        return $_SESSION['live_time'];
    }


    /**
     * Returns the remaining session live time (in minutes)
     *
     * @param  bool  $gmdate  format the time in H:i:s
     *
     * @return mixed the remaining session live time (in minutes)
     */
    public function getRemainingTime(bool $gmdate = false)
    {
        $end = $_SESSION['end_time'] ?? 0;
        $remaining = $end - time();

        if ($gmdate) {
            return gmdate(self::DATE_FORMAT, ($remaining > 0) ? $remaining : 0);
        }

        return ($remaining > 0) ? $remaining : 0;
    }


    /**
     * Set flash variable (will be destroyed on next response)
     *
     * @param  string  $key  the variable name
     * @param  string  $value  the variable value
     */
    public function setFlash(string $key, $value)
    {
        $_SESSION['flash_data'][$key] = $value;
    }


    /**
     * Get flash variable
     *
     * @param  string  $key  the variable name
     *
     * @return object the variable
     */
    public function getFlash(string $key)
    {
        return $_SESSION['flash_data'][$key];
    }


    /**
     * Unset the session data
     */
    public function empty()
    {
        session_unset();
    }


    /**
     * Destroy the session
     */
    public function kill()
    {
        session_destroy();
    }

}