<?php

namespace Core;

class DB
{

    /**
     * Static instance of the connection.
     *
     * @var Core\Connection
     */
    protected static $instance;

    /**
     * DB connection.
     *
     * @var \PDO
     */
    protected static $connection;

    /**
     * The last query executed.
     *
     * @var string
     */
    protected static $lastSQL;


    /**
     * Connects with the database using the constants present in config.php
     */
    public function __construct() {
        try {
            self::$connection = new \PDO(strtolower(getDBMS()) . ":host=" . getServer() . "; dbname=" . getDB() . "",
                getDBUser(), getDBPass(), array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }


    /**
     * Initializes the database connection
     */
    public static function initialize() {
        if (!self::$instance) {
            self::$instance = new self();
        }
    }


    /**
     * Returns the last query executed
     * @return string $lastSQL the last query executed
     */
    public static function getLastSQL() {
        return self::$lastSQL;
    }


    /**
     * Proxy to native PDO methods
     * @param mixed $method the method name
     * @param mixed $args the method arguments
     * @return mixed the function result
     */
    public static function __callStatic($method, $args) {
        return call_user_func_array(array(self::$connection, $method), $args);
    }


    /**
     * Returns the last inserted id in the database
     * @return string the last inserted id in the database
     */
    public static function getLastId() {
        return self::$connection->lastInsertId();
    }


    /**
     * Returns a query result as an associative array
     * @param string $sql the query
     * @param mixed $args the arguments
     * @return array the query result as an associative array
     */
    public static function run(string $sql, $args = []) {
        self::$lastSQL = $sql;
        //Query without args
        if (!$args) {
            $result = self::$connection->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
            if (count($result) <= 1) {
                return $result[0];
            }

            return $result;
        }

        //Query with args
        $args = is_array($args) ? $args : array($args);
        $stmt = self::$connection->prepare($sql);
        $stmt->execute($args);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (count($result) <= 1) {
            return $result[0];
        }

        return $result;
    }


    /**
     * Returns a query result as a json
     * @param string $sql the query
     * @param mixed $args the arguments
     * @return array the query result as a json
     */
    public static function runJson(string $sql, $args = []) {
        return json_encode(self::run($sql, $args));
    }


    /**
     * Export a query to a csv file
     * @param string $filename the filename
     * @param string $sql the query
     * @param mixed $args the arguments
     */
    public static function runCsv(string $filename, string $sql, $args = []) {
        arrayToCsv($filename, self::run($sql, $args));
    }

}