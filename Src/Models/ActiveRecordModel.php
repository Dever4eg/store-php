<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/14/18
 * Time: 23:19
 */

namespace Src\Models;


use Src\App;

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
        $db = App::getDB();
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


    public static function all($params = [])
    {
        $db = self::getDB();

        $sql = 'SELECT * FROM ' . self::getTableName() . self::addSqlParams($params);

        return $db->query($sql);
    }

    public static function getById($id)
    {
        $db = self::getDB();

        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE id=:id';
        $res = $db->query($sql, ['id' => $id]);

        return empty($res) ? false : $res[0];
    }


    public static function findByColumn($col, $value, $params = [])
    {
        $db = self::getDB();

        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE ' . $col . '=:'.$col;
        $pdo_params[':'.$col] = $value;

        $sql .= self::addSqlParams($params);

        $res = $db->query($sql, $pdo_params);

        return $res;
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