<?php

namespace System;

class Connection {

    protected static $mysqli;
    

    /**
     * Connects with the database using the constants present in config.php
     */
    public static function connect() {
        if (Connection::$mysqli == null) {
            try {
                Connection::$mysqli = new \mysqli(SERVER, USER, PASSWORD, DB);
            } catch(Exception $e) {
                throw new \Exception('Conection failed: ' . Connection::$mysqli->connect_error);
            }
        }

        return Connection::$mysqli;
    }

}