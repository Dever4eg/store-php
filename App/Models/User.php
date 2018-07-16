<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 16.07.18
 * Time: 11:40
 */

namespace App\Models;


use Src\Models\ActiveRecordModel;

class User extends ActiveRecordModel
{
    public static function HashPassword($password)
    {
        return sha1($password);
    }
}