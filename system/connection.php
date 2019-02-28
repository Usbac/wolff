<?php

class Connection {

    /**
     * Connects with the database using the constants present in config.php
     * @return object the mysqli connection
     */
    public static function connect() {
        $mysqli = new mysqli(SERVER, USER, PASSWORD, DB);

        if ($mysqli->connect_error) {
            die('Conection failed: ' . $mysqli->connect_error);
        }

        return $mysqli;
    }

}