<?php


namespace Saru\Security;


use Saru\Database\Query;
use Saru\Database\QueryParse;

class Validator
{
    /**
     * Takes a string and the parameters that it will be validated against.
     * Sadly, no specific documentation on this since I need to get an MVP asap.
     * But it's very similar to Laravel... and readable... I think...
     * @param $input string Input to validate.
     * @param mixed ...$params Parameters to validate input against.
     * @return bool Returns true if it passed the validation.
     */
    public static function validate(string $input, ...$params): bool
    {
        foreach ($params as $p)
        {
            switch ($p) {
                case "required":
                    if($input == "" || $input == null)
                    {
                        return false;
                    }
                    break;
                case 'number':
                    if(!is_numeric($input))
                    {
                        return false;
                    }
                    break;
                case 'int':
                    if(!is_numeric($input))
                    {
                        return false;
                    }
                    if(!is_int($input+0))
                    {
                        return false;
                    }
                    break;
                case 'float':
                    if(!is_numeric($input))
                    {
                        return false;
                    }
                    if(!is_float($input+0.0))
                    {
                        return false;
                    }
                    break;
                case (stristr($p, 'min-char')):
                    $min = (int)explode(":", $p)[1];
                    if(strlen($input) < $min )
                    {
                        return false;
                    }
                    break;
                case (stristr($p, 'max-char:')):
                    $max = (int)explode(":", $p)[1];
                    if(strlen($input) > $max)
                    {
                        return false;
                    }
                    break;
                case (stristr($p, 'min-val:')):
                    $min = (int)explode(":", $p)[1];
                    if(!is_numeric($input))
                    {
                        return false;
                    }
                    if((double)$input < $min)
                    {
                        return false;
                    }
                    break;
                case (stristr($p, 'max-val:')):
                    $max = (int)explode(":", $p)[1];
                    if(!is_numeric($input))
                    {
                        return false;
                    }
                    if((double)$input > $max)
                    {
                        return false;
                    }
                    break;
                case (stristr($p, 'either:')):
                    $values = explode(":", $p)[1];
                    $values = explode(",", $values);
                    $flag = false;
                    foreach ($values as $v) {
                        if(strcmp($v, $input) !== 0) {
                            $flag = true;
                        }
                    }
                    if(!$flag) {
                        return false;
                    }
                    break;
                case (stristr($p, 'unique:')):
                    $params = explode(":", $p)[1];
                    $table = explode(",", $params)[0];
                    $col = explode(",", $params)[1];
                    $i = QueryParse::escapeString($input);
                    $exists = Query::getOne("SELECT EXISTS (SELECT * FROM {$table} WHERE {$col}='{$i}') as ex;")['ex'];
                    if($exists == true)
                    {
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }
        return true;
    }
}