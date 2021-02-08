<?php


namespace Saru\Http;


class Request
{
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function body()
    {
        if(Request::method() == "POST" || Request::method() == "GET")
        {
            return $_REQUEST;
        }
        if(Request::method() == "PUT" || Request::method() == "DELETE")
        {
            $raw = file_get_contents('php://input');
            $final = array();
            parse_str($raw, $final);
            return $final;
        }
        return array();
    }

    public static function uri()
    {
        return $_SERVER['REQUEST_URI'];
    }
}