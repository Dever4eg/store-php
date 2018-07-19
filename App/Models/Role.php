<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 16.07.18
 * Time: 11:40
 */

namespace App\Models;


use Src\Database\ActiveRecordModel;

class Role extends ActiveRecordModel
{
    public function relations()
    {
        return [
            'users' => self::hasMany(User::class, 'role_id', 'id')
        ];
    }
}