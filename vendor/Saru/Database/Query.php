<?php


namespace Saru\Database;


use mysqli;

class Query
{

    /**
     * Perform a SQL query using a string. Returns multiple arrays.
     * @param $sql string The SQL query to be performed
     * @param int $mode
     * @return array The values returned from the query.
     */

    public static function getMany(string $sql, $mode = MYSQLI_ASSOC): array
    {
        $conn = new mysqli(env('db_host'), env('db_user'), env('db_pass'), env('db_name'));
        $retval = mysqli_query($conn, $sql);
        $rows = array();
        while($row = $retval->fetch_array($mode))
        {
            $rows[] = $row;
        }
        $conn->close();
        return $rows;
    }

    /**
     * Perform a SQL query using a string. Returns one array.
     * @param $sql string The SQL query to be performed
     * @param int $mode
     * @return array The values returned from the query.
     */
    public static function getOne(string $sql, $mode = MYSQLI_ASSOC): array
    {
        $conn = new mysqli(env('db_host'), env('db_user'), env('db_pass'), env('db_name'));
        $retval = mysqli_query($conn, $sql);
        $conn->close();
        $val = mysqli_fetch_array($retval, $mode);
        if($val) {
            return $val;
        }
        return array();
    }

    /**
     * Perform an INSERT query using SQL. Returns the outcome of the transaction.
     * @param $sql string The SQL insert query to be performed
     * @return bool Whether or not the insert worked.
     */
    public static function insertQuery(string $sql): bool
    {
        $conn = new mysqli(env('db_host'), env('db_user'), env('db_pass'), env('db_name'));
        $retval = mysqli_query($conn, $sql);
        $conn->close();
        return $retval;
    }
}