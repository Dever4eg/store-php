<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/14/18
 * Time: 23:19
 */

namespace Src\Models;


use Src\App;
use Src\DB;
use Src\Exceptions\Http\Error404Exception;

abstract class ActiveRecordModel extends Model
{
    static $table = null;

    public static function getTableName()
    {
        $class = explode('\\', get_called_class());
        $class = array_pop($class);
        $table = !empty(static::$table) ? static::$table : strtolower($class). 's';
        return $table;
    }

    protected static function getDB()
    {
        $db = DB::instance();
        $class = get_called_class();
        $db->setObjectClass($class);

        return $db;
    }

    protected static function addSqlParams($params = [])
    {
        $sql = '';
        foreach ($params as $param => $value) {
            switch (strtolower($param)) {
                case "limit":
                    $sql .= ' LIMIT '. $value;
                    break;
                case "offset":
                    $sql .= ' OFFSET '. $value;
                    break;
            }
        }
        return $sql;
    }

    public static function query()
    {
        return new QueryBuilder( get_called_class());
    }


    public static function all($params = [])
    {
        return self::query()->get();
    }

    public static function getById($id)
    {
        $result = self::query()->where('id', '=', $id)->get();

        return empty($result) ? null : $result[0];
    }


    public function save()
    {
        return empty($this->id) ? $this->insert() : $this->update();
    }

    public function insert()
    {
        $db = self::getDB();

        $params = get_object_vars($this);

        $sql = 'INSERT INTO '. self::getTableName() .
            '('. implode(',', array_keys($params)) .')'.
            'VALUES (:'.implode(', :', array_keys($params)).')';

        $db->execute($sql, $params);
        $this->id = $db->getLastInsertId();
    }

    public function update()
    {
        $db = self::getDB();

        $params = get_object_vars($this);
        $params_str = [];                   // ['key=:key', 'key=:key']

        foreach (array_diff(array_keys($params), ['id']) as $key) {
            $params_str[] = $key .'=:'. $key;
        }

        $sql = 'UPDATE '.self::getTableName() .
            ' SET ' . implode(', ', $params_str) .
            ' WHERE id=:id';

        $db->execute($sql, $params);
        $this->id = $db->getLastInsertId();
    }
}