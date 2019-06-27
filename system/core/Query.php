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
     * or only the specified column of the first element
     *
     * @param  string  $column  the column name to pick
     *
     * @return array the first element of the query results,
     * or only the specified column of the first element
     */
    public function first(string $column = null)
    {
        if (isset($column, $this->rows[0])) {
            return $this->rows[0][$column];
        }

        return $this->rows[0] ?? null;
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

        //Only one column to pick
        if (count($columns) == 1) {
            foreach($this->rows as $row) {
                if (is_string($columns[0]) && array_key_exists($columns[0], $row)) {
                    $rows[] = $row[$columns[0]];
                }
            }

            return $rows;
        }

        //Multiple columns to pick
        foreach($this->rows as $row) {
            $new_row = [];
            foreach ($columns as $column) {
                if (is_string($column) && array_key_exists($column, $row)) {
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
