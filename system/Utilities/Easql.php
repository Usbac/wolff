<?php

namespace Utilities;

use \Core\DB;

class Easql
{

    /**
     * The query table.
     *
     * @var string
     */
    private static $table;

    /**
     * If true make a delete query, if false don't.
     *
     * @var bool
     */
    private static $delete;

    /**
     * Main query sentence.
     *
     * @var string
     */
    private static $sentence;

    /**
     * On statement for a join.
     *
     * @var string
     */
    private static $on;

    /**
     * Join statement.
     *
     * @var string
     */
    private static $join;

    /**
     * If true make a count query, if false don't.
     *
     * @var bool
     */
    private static $count;

    /**
     * If true make a select distinct query, if false don't.
     *
     * @var bool
     */
    private static $distinct;

    /**
     * Conditionals for the query.
     *
     * @var string
     */
    private static $conditional;

    /**
     * Query order by.
     *
     * @var string
     */
    private static $order;


    /**
     * Last query executed by Easql.
     *
     * @var string
     */
    private static $lastQuery;


    /**
     * Indicate the table for the query
     * @param string $table the table
     * @return Easql $this
     */
    public static function table(string $table) {
        self::$table = "FROM $table";
        return new static();
    }


    /**
     * Indicate the table for the inner join in the query
     * @param string $table the table
     * @param string $name the table name for the join
     * @return Easql $this
     */
    public static function inner(string $table, string $name = null) {
        $name = $name ?? $table;
        self::$join .= " INNER JOIN $table as $name";
        return new static();
    }


    /**
     * Indicate the table for the left join in the query
     * @param string $table the table
     * @param string $name the table name for the join
     * @return Easql $this
     */
    public static function left(string $table, string $name = null) {
        $name = $name ?? $table;
        self::$join .= " LEFT JOIN $table as $name";
        return new static();
    }


    /**
     * Indicate the table for the right join in the query
     * @param string $table the table
     * @param string $name the table name for the join
     * @return Easql $this
     */
    public static function right(string $table, string $name = null) {
        $name = $name ?? $table;
        self::$join .= " RIGHT JOIN $table as $name";
        return new static();
    }


    /**
     * Indicate the conditional for the join in the query
     * @param mixed $on the conditionals
     * @return Easql $this
     */
    public static function on($on) {
        if (is_array($on)) {
            $on = implode(' AND ', $on);
        }

        self::$on = "ON $on";
        return new static();
    }


    /**
     * Indicate the selection for the query
     * @param mixed $select the selection
     * @return Easql $this
     */
    public static function select($select = "*") {
        if (is_array($select)) {
            $select = implode(', ', $select);
        }

        self::$sentence = "SELECT $select";
        return new static();
    }


    /**
     * Select All query
     * @param string $table the table for the query
     * @return array the query result as an assosiative array
     */
    public static function selectAll(string $table) {
        $query = DB::run("SELECT * FROM $table");
        self::clear();

        return $query;
    }


    /**
     * Count All query
     * @param string $table the table for the query
     * @return array the query result as an assosiative array
     */
    public static function countAll(string $table) {
        $query = DB::run("SELECT COUNT(*) FROM $table");
        self::clear();

        return $query['COUNT(*)'];
    }


    /**
     * Delete All query
     * @param string $table the table for the query
     */
    public static function deleteAll(string $table) {
        DB::run("DELETE FROM $table");
        self::clear();
    }


    /**
     * Indicate the table for the delete query
     * @param mixed $table the table
     * @return Easql $this
     */
    public static function delete($table) {
        self::$table = $table;
        self::$delete = true;
        return new static();
    }


    /**
     * Indicate the conditionals for the query
     * @param mixed $where the conditionals
     * @return Easql $this
     */
    public static function where($where) {
        if (is_array($where)) {
            $where = implode(' AND ', $where);
        }

        self::$conditional = $where;
        return new static();
    }


    /**
     * Indicate the order for the query
     * @param string $order the order by
     * @return Easql $this
     */
    public static function order(string $order) {
        self::$order = $order;
        return new static();
    }


    /**
     * Indicate the count(*) as selection
     * @return Easql $this
     */
    public static function count() {
        self::$count = true;
        return new static();
    }


    /**
     * Indicate the distinct for the selection of the query
     * @return Easql $this
     */
    public static function distinct() {
        self::$distinct = true;
        return new static();
    }


    /**
     * Get the query constructed
     * @return string the query constructed
     */
    public static function getSQL() {
        self::$sentence = empty(self::$sentence) ? "SELECT *" : self::$sentence;

        if (self::$delete) {
            self::$sentence = "DELETE FROM";
        }

        if (self::$distinct) {
            self::$sentence = str_replace("SELECT", "SELECT DISTINCT", self::$sentence);
        }

        if (self::$count) {
            self::$sentence = "SELECT count(*)";
        }

        self::$conditional = empty(self::$conditional) ? "" : "WHERE " . self::$conditional;
        self::$order = empty(self::$order) ? "" : "ORDER BY" . self::$order;

        return self::$sentence . self::$table . self::$join . self::$on . self::$conditional . self::$order;
    }


    /**
     * Clear all the query variables
     */
    public static function clear() {
        self::$table = '';
        self::$delete = '';
        self::$sentence = '';
        self::$on = '';
        self::$join = '';
        self::$count = '';
        self::$distinct = '';
        self::$conditional = '';
        self::$order = '';
        self::$lastQuery = '';
    }


    /**
     * Get the last query executed by Easql
     * @return string the query
     */
    public static function getLastSQL() {
        return self::$lastQuery;
    }


    /**
     * Execute the last query executed by Easql
     * @return array the query result
     */
    public static function doLastSQL() {
        $query = DB::run(self::getLastSQL());
        self::clear();

        return $query;
    }


    /**
     * Do the query
     * @return array the query result
     */
    public static function do() {
        self::$lastQuery = self::getSQL();
        $query = DB::run(self::getSQL());
        self::clear();

        return $query;
    }


    /**
     * Do a query
     * @return array the query result
     */
    public static function query($sql) {
        self::$lastQuery = self::getSQL();
        $query = DB::run($sql);
        self::clear();

        return $query;
    }

}