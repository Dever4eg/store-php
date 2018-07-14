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

    private static function getTableName($class)
    {
        $class = explode('\\', $class);
        $class = array_pop($class);
        $table = !empty(static::$table) ? static::$table : strtolower($class). 's';
        return $table;
    }

    public static function all()
    {
        $db = App::getDB();
        $class = get_called_class();
        $db->setObjectClass($class);

        $sql = 'SELECT * FROM ' . self::getTableName($class);
        return $db->query($sql);
    }
}