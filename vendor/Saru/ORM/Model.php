<?php


namespace Saru\ORM;

use Saru\Database\Query;
use Saru\Database\QueryParse;

class Model
{
    /**
     * Array representing the Columns for the table of the Model.
     * @var array
     */
    protected array $properties;
    /**
     * Array of keys that shouldn't be part of the output.
     * @var array
     */
    protected array $protected;

    /**
     *
     * @param string $id
     * @return mixed Returns the Model.
     */
    public static function getById(string $id): mixed
    {
        $modelNamespace = get_called_class();
        /** @noinspection PhpUndefinedMethodInspection */
        $tableName = $modelNamespace::getTableName();
        $sql = "SELECT * from {$tableName} where id = {$id};";
        /** @noinspection PhpUndefinedMethodInspection */
        return $modelNamespace::create(Query::getOne($sql));
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        $modelNamespace = get_called_class();
        /** @noinspection PhpUndefinedMethodInspection */
        $tableName = $modelNamespace::getTableName();
        $sqlCount = "SELECT COUNT(*) from {$tableName};";
        $num = Query::getOne($sqlCount, MYSQLI_NUM)[0];
        if(!$num) {
            return array();
        }
        $sqlGetAll = "SELECT * from {$tableName};";
        return Query::getMany($sqlGetAll);
    }

    /**
     * @param string $mode
     * @return false|string
     */
    protected static function getTableName(string $mode = "lowercase")
    {
        $namespace = get_called_class();
        if($namespace != false) {
            $namespaceArray = explode("\\", $namespace);
            $classname = end($namespaceArray);
            return match ($mode) {
                "lowercase" => strtolower($classname),
                "uppercase" => strtoupper($classname),
                default => false,
            };
        } else {
            return false;
        }
    }

    /**
     * Get the Model's properties as an array of Key => Value.
     * @param bool $overrideProtected
     * @return array
     */
    public function get(bool $overrideProtected = false): array
    {
        $propsArray = array();
        foreach ($this->properties as $key)
        {
            if(!in_array($key, $this->protected) || $overrideProtected) {
                if (isset($this->{$key})) {
                    $propsArray[$key] = $this->{$key};
                } else {
                    $propsArray[$key] = null;
                }
            }
        }
        return $propsArray;
    }

    /**
     * Get a Model from an array of Key => Value representing the columns in the table.
     * @param array $props
     * @return mixed
     */
    public static function create(array $props): mixed
    {
        $modelNamespace = get_called_class();
        $model = new $modelNamespace;

        foreach ($props as $key => $value)
        {
            $model->{$key} = $value;
        }

        return $model;
    }

    /**
     * Returns the Model's properties in JSON format
     * @return string
     */
    public function toJson(): string
    {
        $propsArray = $this->get();
        $json = json_encode($propsArray);
        if($json) {
            return $json;
        }
        return "";
    }

    public function save()
    {
        $modelNamespace = get_called_class();
        $propsArray = $this->get(overrideProtected: true);
        /** @noinspection PhpUndefinedMethodInspection */
        $tableName = $modelNamespace::getTableName();
        $params = QueryParse::arrayToQueryParams($propsArray);
        $values = QueryParse::arrayToQueryValues($propsArray);
        if(!isset($this->id)) {
            $sql = "INSERT INTO {$tableName}({$params}) VALUES ({$values});";
            return Query::insertQuery($sql);
        } else {
            $sql = "SELECT EXISTS (SELECT * FROM {$tableName} WHERE id ='{$this->id}');";
            $exists = Query::getOne($sql, MYSQLI_NUM);
            if($exists[0]) {
                unset($propsArray['id']);
                $sqlParams = QueryParse::arrayToUpdateQuery($propsArray);
                $sql = "UPDATE {$tableName} SET {$sqlParams} WHERE id = {$this->id}";
                return Query::insertQuery($sql);
            }
            // The ID is set but it doesn't exist in the DB, so you create a new object with that ID
            $sql = "INSERT INTO {$tableName}({$params}) VALUES ({$values});";
            return Query::insertQuery($sql);
        }
    }
}