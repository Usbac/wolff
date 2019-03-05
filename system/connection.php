<?php

namespace System;

class Connection {

    protected static $connection;
    

    /**
     * Connects with the database using the constants present in config.php
     */
    public static function connect(string $type = 'mysqli') {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $type = strtolower($type);

        //PostgreSQL
        if ($type == 'postgresql') {
            self::$connection = \pg_connect("host=" . SERVER . " dbname=" . DB . " user=" . USER . " password=" . PASSWORD . "");
            return self::$connection;
        }

        //PDO
        if ($type == 'pdo') {
            self::$connection = new \PDO("pgsql:host=" . SERVER . "; dbname=" . DB . "", USER, PASSWORD);
            return self::$connection;
        }
        
        //Mysqli
        try {
            self::$connection = new \mysqli(SERVER, USER, PASSWORD, DB);
        } catch (Exception $e) {
            throw new \Exception('Conection failed: ' . self::$connection->connect_error);
        }

        return self::$connection;
    }

}