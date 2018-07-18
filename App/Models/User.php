<?php


namespace App\Models;


use Src\Models\ActiveRecordModel;


class User extends ActiveRecordModel
{

    public static function relations()
    {
        return [
            "role" => self::hasOne(Role::class, 'role_id', 'id'),
        ];
    }


    public static function HashPassword($password)
    {
        return sha1($password);
    }

    public static function allWithRole($params = [])
    {
        $db = self::getDB();

        $sql = 'SELECT users.*, roles.name as role FROM  users, roles'.
            ' WHERE users.role_id=roles.id'.
            self::addSqlParams($params);

        return $db->query($sql);
    }



    public static function getByIdWithRole($id)
    {
        $db = self::getDB();

        $sql = 'SELECT users.*, roles.name as role FROM users, '. Role::getTableName().
            ' WHERE users.role_id='. Role::getTableName() . '.id'.
            ' AND users.id=:id';


        $res = $db->query($sql, ['id' => $id]);

        return empty($res) ? false : $res[0];
    }

    public static function getByColsWithRole($cols)
    {
        $db = self::getDB();


        $params_sql = [];
        foreach ($cols as $col => $value) {
            $params_sql [] = $col.'=:'.$col;
        }

        $sql = 'SELECT users.*, roles.name as role FROM ' . self::getTableName() .
            ' INNER JOIN ' . Role::getTableName() . ' ON users.role_id=roles.id'.
            ' WHERE ' . implode(' AND ', $params_sql);

        $res = $db->query($sql, $cols);

        return empty($res) ? false : $res[0];
    }

}