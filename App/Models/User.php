<?php


namespace App\Models;


use Src\Models\ActiveRecordModel;
use Src\Models\RelationShip;


class User extends ActiveRecordModel
{


    public static function relations()
    {
        return [
            "role" => self::belongsTo(Role::class, 'role_id', 'id'),
        ];
    }

    public static function hashPassword($password)
    {
        return sha1($password);
    }



}