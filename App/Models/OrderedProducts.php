<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 19.07.18
 * Time: 10:20
 */

namespace App\Models;


use Src\Database\ActiveRecordModel;
use Src\Database\RelationShip;

class OrderedProducts extends ActiveRecordModel
{
    public static $table = 'ordered_products';

    public static function relations(): array
    {
        return [
            'order'     => self::belongsTo(Order::class, 'order_id', 'id'),
            'product'     => self::belongsTo(Product::class, 'product_id', 'id'),
        ];
    }

    public static function query()
    {
        $query =  parent::query();
        return $query->with('product');
    }

    public static function addProductFields($o_product)
    {
        $op_fields = array_keys(get_object_vars($o_product));
        $p_fields = array_keys(get_object_vars($o_product->product));
        $keys_for_adding = array_diff($p_fields, $op_fields);

        foreach ($keys_for_adding as $field) {
            $o_product->$field = $o_product->product->$field;
        }

        return $o_product;
    }

    public static function addProductFieldsForArray(array $o_products)
    {
        $products = [];
        foreach ($o_products as $product) {
            $products[] = self::addProductFields($product);
        }
        return $products;
    }

    public static function fromCart(Cart $cart)
    {
        $products = $cart->all();

        $o_products = [];
        foreach ($products as $product) {
            $o_product = new self();
            $o_product->associate('product', $product);
            $o_product->price = $product->price;
            $o_product->count = $product->count;
            $o_products[] = $o_product;
        }

        return $o_products;
    }
}