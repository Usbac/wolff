<?php

class Session {

    /**
     * Destroy and unset the session if the live time is zero or less
     */
    public function __construct() {
        if (isset($_SESSION['end_time']) && time() >= $_SESSION['end_time']) {
            $this->empty();
            $this->kill();
            return;
        }

        if (!isset($_SESSION['vars_tmp_time'])) {
            $_SESSION['vars_tmp_time'] = array();
            return;
        }

        foreach($_SESSION['vars_tmp_time'] as $key => $value) {
            if (time() >= $value) {
                $this->delete($key);
            }
        }
    }


    /**
     * Get a session variable
     * @param key the variable key 
     * @return variable the variable
     */
    public function get($key = null) {
        if (!isset($key)) {
            return $_SESSION;
        }
        
        return $_SESSION[$key];
    }


    /**
     * Set a session variable
     * @param key the variable key 
     * @param key the variable value
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }


    /**
     * Add time to the session live time (in minutes)
     */
    public function addTime($time) {
        $_SESSION['end_time'] += $time * 60;
    }


    /**
     * Get a live time (in minutes) of a session variable
     * @param key the variable key 
     * @param gmdate return the time in date format 
     * @return integer the variable live time
     */
    public function getVarTime($key, $gmdate = false) {
        $remaining = 0;
        if (isset($_SESSION['vars_tmp_time'][$key])) {
            $remaining = $_SESSION['vars_tmp_time'][$key] - time();
        }

        if ($gmdate) {
            return gmdate('H:i:s', ($remaining > 0)? $remaining:0);
        }
        
        return ($remaining > 0)? $remaining:0;
    }


    /**
     * Set a live time (in minutes) to a session variable
     * @param key the variable key 
     * @param key the variable live time
     */
    public function setVarTime($key, $time = 1) {
        $_SESSION['vars_tmp_time'][$key] = time() + ($time * 60);
    }


    /**
     * Add more live time (in minutes) to a session variable
     * @param key the variable key 
     * @param key the variable time to add
     */
    public function addVarTime($key, $time = 1) {
        $_SESSION['vars_tmp_time'][$key] += ($time * 60);
    }


    /**
     * Delete a session variable
     * @param key the variable key
     */
    public function delete($key) {
        unset($_SESSION[$key]);
    }


    /**
     * Set the session live time (in minutes) starting from 
     * the moment this function is called
     * @param time the time
     */
    public function setTime($time) {
        $_SESSION['live_time'] = ($time * 60);
        $_SESSION['end_time'] = time() + ($time * 60);
    }


    /**
     * Get the established session live time (in minutes)
     * @param int the live time
     * @param gmdate return the time in date format
     */
    public function getTime($gmdate = false) {
        if ($gmdate) {
            return gmdate('H:i:s', $_SESSION['live_time']);
        }
        
        return $_SESSION['live_time'];
    }


    /**
     * Get the remaining session live time (in minutes)
     * @param int the remaining session live time
     * @param gmdate return the time in date format
     */
    public function getRemainingTime($gmdate = false) {
        $remaining = @$_SESSION['end_time'] - time();

        if ($gmdate) {
            return gmdate('H:i:s', ($remaining > 0)? $remaining:0);
        }

        return ($remaining > 0)? $remaining:0;
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