<?php


namespace Saru\Database;


use mysqli;

class QueryParse
{
    /**
     * Escape a string for query using the environment connection.
     * @param $string string String to be escaped.
     * @return string Escaped string.
     */
    public static function escapeString(string $string): string
    {
        $conn = new mysqli(env('db_host'), env('db_user'), env('db_pass'), env('db_name'));
        $escaped = $conn->real_escape_string($string);
        $conn->close();
        return $escaped;
    }

    /**
     * Parse an array dynamically to it's parameters for use in SQL.
     * @param $params array Array to be converted as query parameters.
     * @return string Parsed array
     */
    public static function arrayToQueryParams(array $params): string
    {
        $params = array_keys($params);
        $parsed = "";
        for ($i = 0; $i < count($params); $i++)
        {
            $parsed .= $params[$i];
            if($i+1 != count($params))
            {
                $parsed .= ',';
            }
        }
        return $parsed;
    }

    /**
     * Parse an array dynamically to it's values for use in SQL.
     * @param $values array Array to be converted as query parameters.
     * @return string Parsed array
     */
    public static function arrayToQueryValues(array $values): string
    {
        $parsed = "";
        $i = 1;
        foreach ($values as $v)
        {
            $parsed .= "'".$v."'";
            if($i != count($values))
            {
                $parsed .= ",";
            }
            $i++;
        }
        return $parsed;
    }

    /**
     * @param array $values
     * @return string
     */
    public static function arrayToUpdateQuery(array $arrayQuery): string
    {
        $params = array_keys($arrayQuery);
        $parsed = "";
        for ($i = 0; $i < count($params); $i++)
        {
            $key = $params[$i];
            $parsed .= $key."='".$arrayQuery[$key]."'";
            if($i+1 != count($params))
            {
                $parsed .= ',';
            }
        }
        return $parsed;
    }
}