<?php

namespace Core;

use PDO;

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
    const ERROR_MODE = PDO::ERRMODE_EXCEPTION;
    const NAMES_MODE = 'SET NAMES utf8';


    /**
     * Connects with the database using the constants present in the config file
     */
    public function __construct()
    {
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => self::NAMES_MODE,
            PDO::ATTR_DEFAULT_FETCH_MODE => self::FETCH_MODE,
            PDO::ATTR_ERRMODE            => self::ERROR_MODE
        ];

        self::$connection = Factory::connection($options);
    }


    /**
     * Initializes the database connection
     */
    public static function initialize()
    {
        if (self::isEnabled() && !self::$instance) {
            self::$instance = new self();
        }
    }


    /**
     * Returns true if the database is enabled, false otherwise
     * @return bool true if the database is enabled, false otherwise
     */
    public static function isEnabled()
    {
        return CONFIG['db_on'];
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
        return call_user_func_array([ self::getPdo(), $method], $args);
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
        self::$last_args = is_array($args) ? $args : [$args];

        //Query without args
        if (!isset(self::$last_args)) {
            $result = self::getPdo()->query($sql);
            return Factory::query($result);
        }

        //Query with args
        $stmt = self::getPdo()->prepare($sql);
        $stmt->execute(self::$last_args);
        self::$last_stmt = $stmt;

        return Factory::query($stmt);
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
        $table = self::escape($table);

        try {
            $result = self::getPdo()->query("SELECT 1 FROM $table LIMIT 1");
        } catch (\Exception $e) {
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
        $table = self::escape($table);
        $column = self::escape($column);

        $result = self::getPdo()->query("SHOW COLUMNS FROM $table LIKE $column");
        if (is_bool($result)) {
            return false;
        }

        return !empty($result->fetchAll());
    }


    /**
     * Returns the database schema
     *
     * @return array|bool the database schema or false if no tables exist in the database
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
     * @return array|bool the table schema from the database,
     * or false if the table columns doesn't exists
     */
    public static function getTableSchema(string $table)
    {
        $table = self::escape($table);
        $result = self::getPdo()->query("SHOW COLUMNS FROM $table");

        if (is_bool($result)) {
            return false;
        }

        return $result->fetchAll();
    }


    /**
     * Returns the result of a SELECT ALL query
     * WARNING: The conditions parameter must be manually escaped
     *
     * @param  string  $table  the table for the query
     * @param  string  $conditions  the select conditions
     * @param  mixed  $args the query arguments
     *
     * @return array the query result as an assosiative array
     */
    public static function selectAll(string $table, string $conditions = '1', $args = null)
    {
        $table = self::escape($table);

        return DB::run("SELECT * FROM $table WHERE $conditions", $args)->get();
    }


    /**
     * Returns the result of a SELECT COUNT(*) query
     * WARNING: The conditions parameter must be manually escaped
     *
     * @param  string  $table  the table for the query
     * @param  string  $conditions  the select conditions
     * @param  array  $args the query arguments
     *
     * @return string the query result
     */
    public static function countAll(string $table, string $conditions = '1', array $args = null)
    {
        $table = self::escape($table);
        $result = DB::run("SELECT COUNT(*) FROM $table WHERE $conditions", $args)->first();

        return empty($result) ? 0 : $result['COUNT(*)'];
    }


    /**
     * Moves rows from one table to another, deleting the rows of the
     * original table in the process
     * WARNING: The conditions parameter must be manually escaped
     * NOTE: In case of errors, the changes are completely rolled back
     *
     * @param  string  $ori_table  the origin table
     * @param  string  $dest_table  the destination table
     * @param  string  $conditions  the conditions
     * @param  array  $args the query arguments
     *
     * @return bool true if the transaction has been made successfully, false otherwise
     */
    public static function moveRows(string $ori_table, string $dest_table, string $conditions = '1', $args = null)
    {
        $ori_table = self::escape($ori_table);
        $dest_table = self::escape($dest_table);

        try {
            $insert_stat = self::getPdo()->prepare("INSERT INTO $dest_table SELECT * FROM $ori_table WHERE $conditions");
            $delete_stat = self::getPdo()->prepare("DELETE FROM $ori_table WHERE $conditions");

            self::getPdo()->beginTransaction();

            $insert_stat->execute($args);
            $delete_stat->execute($args);

            self::getPdo()->commit();
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollback();
                return false;
            }
        }

        return true;
    }


    /**
     * Runs a DELETE query
     * WARNING: The conditions parameter must be manually escaped
     *
     * @param  string  $table  the table for the query
     * @param  string  $conditions  the select conditions
     * @param  array  $args the query arguments
     *
     * @return array the query result as an assosiative array
     */
    public static function deleteAll(string $table, string $conditions = '1', array $args = null)
    {
        $table = self::escape($table);

        return DB::run("DELETE FROM $table WHERE $conditions", $args)->get();
    }


    /**
     * Returns the string escaped
     * Any character that is not a letter, number or underscore is removed.
     *
     * @param  string  $str  the string
     *
     * @return array the string escaped
     */
    public static function escape($str)
    {
        return preg_replace('/[^A-Za-z0-9_]+/', '', $str);
    }

}
