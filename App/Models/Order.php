<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 19.07.18
 * Time: 10:10
 */

namespace App\Models;



use Src\Database\ActiveRecordModel;

class Order extends ActiveRecordModel
{
    public static function relations()
    {
        return [
            'user'  => self::belongsTo(User::class, 'user_id', 'id'),
            'products'  => self::hasMany(OrderedProducts::class, 'order_id', 'id')
        ];

    }
}