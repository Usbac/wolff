<?php

namespace System\Library;

class Easql {

    /**
     * Static instance of the connection.
     *
     * @var Core\Connection
     */
    private $db;

    /**
     * The query table.
     *
     * @var string
     */
    private $table;

    /**
     * If true make a delete query, if false don't.
     *
     * @var bool
     */
    private $delete;

    /**
     * Main query sentence.
     *
     * @var string
     */
    private $sentence;

    /**
     * On statement for a join.
     *
     * @var string
     */
    private $on;

    /**
     * Join statement.
     *
     * @var string
     */
    private $join;

    /**
     * If true make a count query, if false don't.
     *
     * @var bool
     */
    private $count;

    /**
     * If true make a select distinct query, if false don't.
     *
     * @var bool
     */
    private $distinct;

    /**
     * Conditionals for the query.
     *
     * @var string
     */
    private $conditional;

    /**
     * Query order by.
     *
     * @var string
     */
    private $order;


    /**
     * Last query executed by Easql.
     *
     * @var string
     */
    private $lastQuery;


    public function __construct($db) {
        $this->db = &$db;
    }


    /**
     * Indicate the table for the query
     * @param string $table the table
     * @return Easql $this
     */
    public function table(string $table) {
        $this->table = "FROM $table";
        return $this;
    }


    /**
     * Indicate the table for the inner join in the query
     * @param string $table the table
     * @param string $name the table name for the join
     * @return Easql $this
     */
    public function inner(string $table, string $name = null) {
        $name = $name?? $table;
        $this->join .= " INNER JOIN $table as $name";
        return $this;
    }


    /**
     * Indicate the table for the left join in the query
     * @param string $table the table
     * @param string $name the table name for the join
     * @return Easql $this
     */
    public function left(string $table, string $name = null) {
        $name = $name?? $table;
        $this->join .= " LEFT JOIN $table as $name";
        return $this;
    }


    /**
     * Indicate the table for the right join in the query
     * @param string $table the table
     * @param string $name the table name for the join
     * @return Easql $this
     */
    public function right(string $table, string $name = null) {
        $name = $name?? $table;
        $this->join .= " RIGHT JOIN $table as $name";
        return $this;
    }


    /**
     * Indicate the conditional for the join in the query
     * @param mixed $on the conditionals
     * @return Easql $this
     */
    public function on($on) {
        if (is_array($on)) {
            $on = implode(' AND ', $on);
        }

        $this->on = "ON $on";
        return $this;
    }


    /**
     * Indicate the selection for the query
     * @param mixed $select the selection
     * @return Easql $this
     */
    public function select($select = "*") {
        if (is_array($select)) {
            $select = implode(', ', $select);
        }

        $this->sentence = "SELECT $select";
        return $this;
    }


    /**
     * Select All query
     * @param string $table the table for the query
     * @return array the query result as an assosiative array
     */
    public function selectAll(string $table) {
        $query = $this->db->query("SELECT * FROM $table");
        $this->clear();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    
    /**
     * Count All query
     * @param string $table the table for the query
     * @return array the query result as an assosiative array
     */
    public function countAll(string $table) {
        $query = $this->db->query("SELECT COUNT(*) FROM $table");
        $this->clear();

        return $query->fetchAll(\PDO::FETCH_ASSOC)['COUNT(*)'];
    }

    
    /**
     * Delete All query
     * @param string $table the table for the query
     */
    public function deleteAll(string $table) {
        $this->db->query("DELETE FROM $table");
        $this->clear();
    }


    /**
     * Indicate the table for the delete query
     * @param mixed $table the table
     * @return Easql $this
     */
    public function delete($table) {
        $this->table = $table;
        $this->delete = true;
        return $this;
    }


    /**
     * Indicate the conditionals for the query
     * @param mixed $where the conditionals
     * @return Easql $this
     */
    public function where($where) {
        if (is_array($where)) {
            $where = implode(' AND ', $where);
        }

        $this->conditional = $where;
        return $this;
    }


    /**
     * Indicate the order for the query
     * @param string $order the order by
     * @return Easql $this
     */
    public function order(string $order) {
        $this->order = $order;
        return $this;
    }


    /**
     * Indicate the count(*) as selection
     * @return Easql $this
     */
    public function count() {
        $this->count = true;
        return $this;
    }
    

    /**
     * Indicate the distinct for the selection of the query
     * @return Easql $this
     */
    public function distinct() {
        $this->distinct = true;
        return $this;
    }


    /**
     * Get the query constructed
     * @return string the query constructed
     */
    public function getSQL() {
        $this->sentence = empty($this->sentence)? "SELECT *": $this->sentence;

        if ($this->delete) {
            $this->sentence = "DELETE FROM";
        }

        if ($this->distinct) {
            $this->sentence = str_replace("SELECT", "SELECT DISTINCT", $this->sentence);
        }

        if ($this->count) {
            $this->sentence = "SELECT count(*)";
        }

        $this->conditional = empty($this->conditional)? "": "WHERE $this->conditional";
        $this->order = empty($this->order)? "": "ORDER BY $this->order";

        return "$this->sentence $this->table $this->join $this->on $this->conditional $this->order";
    }


    /**
     * Clear all the query variables
     */
    public function clear() {
        foreach($this as $key => $value) {
            $this->$key = null;
        }
    }


    /**
     * Get the last query executed by Easql
     * @return string the query
     */
    public function getLastSQL() {
        return $this->lastQuery;
    }


    /**
     * Execute the last query executed by Easql
     * @return array the query result
     */
    public function doLastQuery() {
        $query = $this->db->query($this->getLastSQL());
        $this->clear();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Do the query
     * @return array the query result
     */
    public function do() {
        $this->lastQuery = $this->getSQL();
        $query = $this->db->query($this->getSQL());
        $this->clear();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    
    /**
     * Do a query
     * @return array the query result
     */
    public function query($sql) {
        $this->lastQuery = $this->getSQL();
        $query = $this->db->query($sql);
        $this->clear();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

}