<?php

namespace Core;

class Session
{

    /**
     * Destroy and unset the session if the live time is zero or less
     */
    public function __construct() {
        $_SESSION['flash_data'] = array();

        if (isset($_SESSION['end_time']) && time() >= $_SESSION['end_time']) {
            $this->empty();
            $this->kill();
            return;
        }

        if (!isset($_SESSION['vars_tmp_time'])) {
            $_SESSION['vars_tmp_time'] = array();
            return;
        }

        foreach ($_SESSION['vars_tmp_time'] as $key => $value) {
            if (time() >= $value) {
                $this->delete($key);
            }
        }
    }


    /**
     * Get a session variable
     * @param string $key the variable key
     * @return object the variable
     */
    public function get(string $key = null) {
        if (!isset($key)) {
            return $_SESSION;
        }

        return $_SESSION[$key];
    }


    /**
     * Set a session variable
     * @param string $key the variable key
     * @param string value the variable value
     */
    public function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }


    /**
     * Add time to the session live time (in minutes)
     * @param int $time the session live time to add
     */
    public function addTime(int $time) {
        $_SESSION['end_time'] += $time * 60;
    }


    /**
     * Get a live time (in minutes) of a session variable
     * @param string $key the variable key
     * @param bool $gmdate return the time in date format
     * @return int the variable live time
     */
    public function getVarTime(string $key, bool $gmdate = false) {
        $remaining = 0;
        if (isset($_SESSION['vars_tmp_time'][$key])) {
            $remaining = $_SESSION['vars_tmp_time'][$key] - time();
        }

        if ($gmdate) {
            return gmdate('H:i:s', ($remaining > 0) ? $remaining : 0);
        }

        return ($remaining > 0) ? $remaining : 0;
    }


    /**
     * Set a live time (in minutes) to a session variable
     * @param string $key the variable key
     * @param int $time the variable live time
     */
    public function setVarTime(string $key, int $time = 1) {
        $_SESSION['vars_tmp_time'][$key] = time() + ($time * 60);
    }


    /**
     * Add more live time (in minutes) to a session variable
     * @param string $key the variable key
     * @param int $time the variable time to add
     */
    public function addVarTime(string $key, int $time = 1) {
        $_SESSION['vars_tmp_time'][$key] += ($time * 60);
    }


    /**
     * Delete a session variable
     * @param string $key the variable key
     */
    public function delete(string $key) {
        unset($_SESSION[$key]);
    }


    /**
     * Set the session live time (in minutes) starting from
     * the moment this function is called
     * @param time $time the time
     */
    public function setTime(int $time) {
        $_SESSION['live_time'] = ($time * 60);
        $_SESSION['end_time'] = time() + ($time * 60);
    }


    /**
     * Returns the established session live time (in minutes)
     * @param bool $gmdate format the time in H:i:s
     * @return mixed the established session live time (in minutes)
     */
    public function getTime(bool $gmdate = false) {
        if ($gmdate) {
            return gmdate('H:i:s', $_SESSION['live_time']);
        }

        return $_SESSION['live_time'];
    }


    /**
     * Returns the remaining session live time (in minutes)
     * @param bool $gmdate format the time in H:i:s
     * @return mixed the remaining session live time (in minutes)
     */
    public function getRemainingTime(bool $gmdate = false) {
        $end = $_SESSION['end_time'] ?? 0;
        $remaining = $end - time();

        if ($gmdate) {
            return gmdate('H:i:s', ($remaining > 0) ? $remaining : 0);
        }

        return ($remaining > 0) ? $remaining : 0;
    }


    /**
     * Set flash variable (will be destroyed on next response)
     * @param string $key the variable name
     * @param string $value the variable value
     */
    public function setFlash(string $key, $value) {
        $_SESSION['flash_data'][$key] = $value;
    }


    /**
     * Get flash variable
     * @param string $key the variable name
     * @return object the variable
     */
    public function getFlash(string $key) {
        return $_SESSION['flash_data'][$key];
    }


    /**
     * Unset the session data
     */
    public function empty() {
        session_unset();
    }


    /**
     * Destroy the session
     */
    public function kill() {
        session_destroy();
    }

}