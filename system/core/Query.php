<?php

namespace Core;

class Query
{

    /**
     * The query result.
     *
     * @var array
     */
    private $rows;


    public function __construct($results)
    {
        $this->rows = $results;
    }


    /**
     * Returns the query results
     *
     * @return array the query results
     */
    public function get()
    {
        return $this->rows;
    }


    /**
     * Returns the query results as a Json
     *
     * @return string the query results as a Json
     */
    public function toJson()
    {
        return json_encode($this->rows);
    }


    /**
     * Returns the first element of the query results
     *
     * @return array the first element of the query results
     */
    public function first()
    {
        return $this->rows[0] ?? null;
    }


    /**
     * Returns only the specified column of the query result
     *
     * @param  string  $column  the column name to pick
     *
     * @return array only the specified column of the query result
     */
    public function pick(string $column)
    {
        $rows = [];

        foreach($this->rows as $row) {
            $rows[] = array_key_exists($column, $row) ? $row[$column] : $row;
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
        return count($this->rows);
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
        return array_slice($this->rows, $start, $end);
    }


    /**
     * Var dump the query results
     */
    public function dump()
    {
        var_dump($this->rows);
    }


    /**
     * Print the query results in a nice looking way
     */
    public function printr()
    {
        printr($this->rows);
    }


    /**
     * Var dump the query results and die
     */
    public function dumpd()
    {
        dumpd($this->rows);
    }


    /**
     * Print the query results in a nice looking way and die
     */
    public function printrd()
    {
        printrd($this->rows);
    }
}
