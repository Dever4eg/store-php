<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 16.07.18
 * Time: 17:25
 */

namespace App\Services;


use Src\App;

class Cart
{

    public function all()
    {
        return App::getSession()->get('cart') ?? [];
    }


    public function add($product)
    {
        $products = $this->all();
        $products[] = $product;
        App::getSession()->set('cart', $products);

        return $this;
    }

    public function remove($id)
    {
        $products = $this->all();
        unset($products[$id]);
        App::getSession()->set('cart', $products);

        return $this;
    }

}