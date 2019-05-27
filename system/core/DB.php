<?php

namespace Core;

use PDO;
use PDOException;

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
     * @var PDO
     */
    protected static $connection;

    /**
     * The last query executed.
     *
     * @var string
     */
    protected static $last_sql;

    /**
     * The arguments of the last query executed.
     *
     * @var array
     */
    protected static $last_args;

    /**
     * The last PDO statement executed.
     *
     * @var PDOStatement
     */
    protected static $last_stmt;

    const FETCH_MODE = PDO::FETCH_ASSOC;
    const NAMES_MODE = 'SET NAMES utf8';


    /**
     * Connects with the database using the constants present in system/config.php
     */
    public function __construct()
    {
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => self::NAMES_MODE,
            PDO::ATTR_DEFAULT_FETCH_MODE => self::FETCH_MODE
        ];

        self::$connection = Factory::connection($options);
    }


    /**
     * Initializes the database connection
     */
    public static function initialize()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
    }


    /**
     * Returns the PDO connection
     * @return PDO the PDO connection
     */
    public static function getPdo()
    {
        return self::$connection;
    }


    /**
     * Set the connection fetch mode
     *
     * @param  int  $mode  the connection fetch mode
     */
    public static function setFetchMode(int $mode = self::FETCH_MODE)
    {
        self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $mode);
    }


    /**
     * Returns the last query executed
     * @return string the last query executed
     */
    public static function getLastSql()
    {
        return self::$last_sql;
    }


    /**
     * Returns the arguments of the last query executed
     * @return array the arguments of the last query executed
     */
    public static function getLastArgs()
    {
        return self::$last_args;
    }


    /**
     * Returns the last prepared PDO statement
     * @return PDOStatement the last prepared PDO statement
     */
    public static function getLastStmt()
    {
        return self::$last_stmt;
    }


    /**
     * Returns the last inserted id in the database
     * @return string the last inserted id in the database
     */
    public static function getLastId()
    {
        return self::getPdo()->lastInsertId();
    }


    /**
     * Returns the number of rows affected by the last query
     * @return int|null the number of rows affected by the last query
     */
    public static function getAffectedRows()
    {
        return self::$last_stmt ? self::$last_stmt->rowCount() : null;
    }


    /**
     * Proxy to native PDO methods
     *
     * @param  mixed  $method  the method name
     * @param  mixed  $args  the method arguments
     *
     * @return mixed the function result
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::getPdo(), $method), $args);
    }


    /**
     * Returns a query result
     *
     * @param  string  $sql  the query
     * @param  mixed  $args  the arguments
     *
     * @return mixed the query result
     */
    public static function run(string $sql, $args = [])
    {
        self::$last_sql = $sql;
        self::$last_args = is_array($args) ? $args : array($args);

        //Query without args
        if (!isset(self::$last_args)) {
            $result = self::getPdo()->query($sql)->fetchAll();

            return $result;
        }

        //Query with args
        $stmt = self::getPdo()->prepare($sql);
        $stmt->execute(self::$last_args);
        self::$last_stmt = $stmt;

        $result = $stmt->fetchAll();

        return $result;
    }


    /**
     * Returns a query result as a json
     *
     * @param  string  $sql  the query
     * @param  mixed  $args  the arguments
     *
     * @return string the query result as a json
     */
    public static function runJson(string $sql, $args = [])
    {
        return json_encode(self::run($sql, $args));
    }


    /**
     * Run the last query executed
     *
     * @return mixed the last query result
     */
    public static function runLastSql()
    {
        return run(self::getLastSql(), self::getLastArgs());
    }


    /**
     * Returns true if the specified table exists in the database, false otherwise
     *
     * @param  string  $table  the table name
     *
     * @return bool true if the specified table exists in the database, false otherwise
     */
    public static function tableExists(string $table)
    {
        try {
            $result = self::getPdo()->query("SELECT 1 FROM $table LIMIT 1");
        } catch (Exception $e) {
            return false;
        }

        return $result !== false;
    }


    /**
     * Returns true if the specified column exists in the table of the database, false otherwise
     *
     * @param  string  $table  the table name
     * @param  string  $column  the column name
     *
     * @return bool true if the specified column exists in the table of the database, false otherwise
     */
    public static function columnExists(string $table, string $column)
    {
        $result = self::getPdo()->query("SHOW COLUMNS FROM $table LIKE '$column'");
        if (is_bool($result)) {
            return false;
        }

        return !empty($result->fetchAll());
    }


    /**
     * Returns the database schema
     *
     * @return array|bool the database schema
     */
    public static function getSchema()
    {
        $tables = self::getPdo()->query("SHOW TABLES");

        if (is_bool($tables)) {
            return false;
        }

        $database = [];

        while ($table = $tables->fetch(PDO::FETCH_NUM)[0]) {
            $database[$table] = self::getTableSchema($table);
        }

        return $database;
    }


    /**
     * Returns the table schema from the database
     *
     * @param  string  $table  the table name
     *
     * @return array|bool the table schema from the database
     */
    public static function getTableSchema(string $table)
    {
        $result = self::getPdo()->query("SHOW COLUMNS FROM $table");
        if (is_bool($result)) {
            return false;
        }

        return $result->fetchAll();
    }


    /**
     * Returns the result of a SELECT ALL query
     *
     * @param  string  $table  the table for the query
     * @param  string  $conditions  the select conditions
     * @param  mixed  $args the query arguments
     *
     * @return array the query result as an assosiative array
     */
    public static function selectAll(string $table, string $conditions = '1', $args = null)
    {
        return DB::run("SELECT * FROM $table WHERE $conditions", $args);
    }


    /**
     * Returns the result of a SELECT COUNT(*) query
     *
     * @param  string  $table  the table for the query
     * @param  string  $conditions  the select conditions
     * @param  mixed  $args the query arguments
     *
     * @return array the query result as an assosiative array
     */
    public static function countAll(string $table, string $conditions = '1', $args = null)
    {
        return DB::run("SELECT COUNT(*) FROM $table WHERE $conditions", $args)[0]['COUNT(*)'];
    }


    /**
     * Runs a DELETE query
     *
     * @param  string  $table  the table for the query
     * @param  string  $conditions  the select conditions
     * @param  mixed  $args the query arguments
     *
     * @return array the query result as an assosiative array
     */
    public static function deleteAll(string $table, string $conditions = '1', $args = null)
    {
        return DB::run("DELETE FROM $table WHERE $conditions", $args);
    }

}
