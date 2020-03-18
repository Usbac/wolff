<?php

namespace Wolff\Core;

use PDO;

class DB
{

    const DEFAULT_OPTIONS = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
    ];

    /**
     * DB connection.
     *
     * @var PDO
     */
    protected $connection;

    /**
     * The last query executed.
     *
     * @var string
     */
    protected $last_sql;

    /**
     * The arguments of the last query executed.
     *
     * @var array
     */
    protected $last_args;

    /**
     * The last PDO statement executed.
     *
     * @var \PDOStatement
     */
    protected $last_stmt;


    /**
     * Initializes the database connection
     *
     * @param  array  $data  The array containing database authentication data
     * @param  array  $options  The PDO connection options
     */
    public function __construct(array $data = null, array $options = null)
    {
        if ($data === null) {
            $data = CONFIG;
        }

        if ($options === null) {
            $options = self::DEFAULT_OPTIONS;
        }

        $this->connection = Factory::connection($data, $options);
    }


    /**
     * Returns the PDO connection
     * @return PDO the PDO connection
     */
    public function getPdo()
    {
        return $this->connection;
    }


    /**
     * Returns the last query executed
     * @return string the last query executed
     */
    public function getLastSql()
    {
        return $this->last_sql;
    }


    /**
     * Returns the arguments of the last query executed
     * @return array the arguments of the last query executed
     */
    public function getLastArgs()
    {
        return $this->last_args;
    }


    /**
     * Returns the last prepared PDO statement
     * @return \PDOStatement the last prepared PDO statement
     */
    public function getLastStmt()
    {
        return $this->last_stmt;
    }


    /**
     * Returns the last inserted id in the database
     * @return string the last inserted id in the database
     */
    public function getLastId()
    {
        return $this->connection->lastInsertId();
    }


    /**
     * Returns the number of rows affected by the last query
     * @return int|null the number of rows affected by the last query
     */
    public function getAffectedRows()
    {
        return $this->last_stmt ? $this->last_stmt->rowCount() : null;
    }


    /**
     * Proxy to native PDO methods
     *
     * @param  mixed  $method  the method name
     * @param  mixed  $args  the method arguments
     *
     * @return mixed the function result
     */
    public function __call($method, $args)
    {
        return call_user_func_array([ $this->connection, $method ], $args);
    }


    /**
     * Returns a query result object
     *
     * @param  string  $sql  the query
     * @param  mixed  $args  the arguments
     *
     * @return mixed the query result object
     */
    public function query(string $sql, $args = [])
    {
        $this->last_sql = $sql;
        $this->last_args = is_array($args) ? $args : [$args];

        //Query without args
        if (!isset($this->last_args)) {
            $result = $this->connection->query($sql);
            return Factory::query($result);
        }

        //Query with args
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->last_args);
        $this->last_stmt = $stmt;

        return Factory::query($stmt);
    }


    /**
     * Runs the last query executed
     *
     * @return mixed the last query result
     */
    public function runLastSql()
    {
        return $this->run($this->getLastSql(), $this->getLastArgs());
    }


    /**
     * Returns true if the specified table exists in the database, false otherwise
     *
     * @param  string  $table  the table name
     *
     * @return bool true if the specified table exists in the database, false otherwise
     */
    public function tableExists(string $table)
    {
        $table = $this->escape($table);

        try {
            $result = $this->connection->query("SELECT 1 FROM $table LIMIT 1");
        } catch (\Exception $err) {
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
    public function columnExists(string $table, string $column)
    {
        $table = $this->escape($table);
        $column = $this->escape($column);

        $result = $this->connection->query("SHOW COLUMNS FROM $table LIKE $column");
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
    public function getSchema()
    {
        $tables = $this->connection->query('SHOW TABLES');

        if (is_bool($tables)) {
            return false;
        }

        $database = [];

        while ($table = $tables->fetch(PDO::FETCH_NUM)[0]) {
            $database[$table] = $this->getTableSchema($table);
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
    public function getTableSchema(string $table)
    {
        $table = $this->escape($table);
        $result = $this->connection->query("SHOW COLUMNS FROM $table");

        if (is_bool($result)) {
            return false;
        }

        return $result->fetchAll();
    }


    /**
     * Inserts an array into the specified table
     * or null in case of errors
     *
     * @param  string  $table  the table for the query
     * @param  array  $data  the data to insert
     *
     * @return mixed the query result object or null in
     * case of errors
     */
    public function insert(string $table, array $data)
    {
        if (empty($data) || !isAssoc($data)) {
            return null;
        }

        $table = $this->escape($table);
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(1, count($data), '?'));

        return $this->query("INSERT INTO $table ($columns) VALUES ($values)", array_values($data));
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
    public function selectAll(string $table, string $conditions = '1', $args = null)
    {
        $table = $this->escape($table);

        return $this->query("SELECT * FROM $table
            WHERE $conditions", $args)->get();
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
    public function countAll(string $table, string $conditions = '1', array $args = null)
    {
        $table = $this->escape($table);
        $result = $this->query("SELECT COUNT(*) FROM $table
            WHERE $conditions", $args)->first();

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
    public function moveRows(string $ori_table, string $dest_table, string $conditions = '1', $args = null)
    {
        $ori_table = $this->escape($ori_table);
        $dest_table = $this->escape($dest_table);

        try {
            $insert_stat = $this->connection->prepare("INSERT INTO $dest_table SELECT * FROM $ori_table WHERE $conditions");
            $delete_stat = $this->connection->prepare("DELETE FROM $ori_table WHERE $conditions");

            $this->connection->beginTransaction();

            $insert_stat->execute($args);
            $delete_stat->execute($args);

            $this->connection->commit();
        } catch (\Exception $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollback();
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
    public function deleteAll(string $table, string $conditions = '1', array $args = null)
    {
        $table = $this->escape($table);

        return $this->query("DELETE FROM $table
            WHERE $conditions", $args)->get();
    }


    /**
     * Returns the string escaped
     * Any character that is not a letter, number or underscore is removed.
     *
     * @param  string  $str  the string
     *
     * @return array the string escaped
     */
    public function escape($str)
    {
        return preg_replace('/[^A-Za-z0-9_]+/', '', $str);
    }

}
