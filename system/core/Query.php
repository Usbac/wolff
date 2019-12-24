<?php

namespace Core;

class Query
{

    /**
     * The query statement.
     *
     * @var array
     */
    private $stmt;


    public function __construct($stmt)
    {
        $this->stmt = $stmt;
    }


    /**
     * Returns the query results
     *
     * @return array the query results
     */
    public function get()
    {
        return $this->stmt->fetchAll();
    }


    /**
     * Returns the query results as a Json
     *
     * @return string the query results as a Json
     */
    public function toJson()
    {
        return json_encode($this->get());
    }


    /**
     * Returns the first element of the query results
     * or only the specified column of the first element
     *
     * @param  string  $column  the column name to pick
     *
     * @return array the first element of the query results,
     * or only the specified column of the first element
     */
    public function first(string $column = null)
    {
        $first = $this->get()[0];
        if (isset($column, $first)) {
            return $first[$column];
        }

        return $first ?? null;
    }


    /**
     * Returns only the specified column/s of the query result
     *
     * @return array only the specified column/s of the query result
     */
    public function pick()
    {
        $rows = [];
        $columns = func_get_args();
        $result = $this->get();

        //Only one column to pick
        if (count($columns) == 1) {
            foreach($result as $row) {
                if (array_key_exists($columns[0], $row)) {
                    $rows[] = $row[$columns[0]];
                }
            }

            return $rows;
        }

        //Multiple columns to pick
        foreach($result as $row) {
            $new_row = [];
            foreach ($columns as $column) {
                if (array_key_exists($column, $row)) {
                    $new_row[$column] = $row[$column];
                }
            }

            array_push($rows, $new_row);
        }

        return $rows;
    }


    /**
     * Returns the number of rows in the query results
     *
     * @return int the number of rows in the query results
     */
    public function count()
    {
        return count($this->get());
    }


    /**
     * Returns the query result sliced
     *
     * @param  int  $start  the offset
     * @param  int  $end  the length
     *
     * @return array the query result sliced
     */
    public function limit(int $start, int $end)
    {
        return array_slice($this->get(), $start, $end);
    }


    /**
     * Var dump the query results
     */
    public function dump()
    {
        var_dump($this->get());
    }


    /**
     * Print the query results in a nice looking way
     */
    public function printr()
    {
        printr($this->get());
    }


    /**
     * Var dump the query results and die
     */
    public function dumpd()
    {
        dumpd($this->get());
    }


    /**
     * Print the query results in a nice looking way and die
     */
    public function printrd()
    {
        printrd($this->get());
    }
}
