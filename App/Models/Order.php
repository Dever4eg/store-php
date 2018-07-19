<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 19.07.18
 * Time: 10:10
 */

namespace App\Models;


use Src\Models\ActiveRecordModel;

class Order extends ActiveRecordModel
{
    public static function relations()
    {
        return [
//            'products'  => $this->hasMany
        ];
    }
}