<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 16.07.18
 * Time: 17:25
 */

namespace App\Models;


use Src\App;

class Cart
{

    public function all()
    {
        return App::getSession()->get('cart') ?? [];
    }

    public function getById($id)
    {
        return App::getSession()->get('cart')[$id] ?? null;
    }

    public function exist(Product $product)
    {
        return $this->getById($product->id) !== null;
    }

    public function save(Product $product)
    {
        $products = $this->all();

        $products[$product->id] = $product;
        App::getSession()->set('cart', $products);

        return $this;
    }



    public function add(Product $product)
    {
        if($this->exist($product)) {
            return $this;
        }
        $product->count = 1;

        return $this->save($product);
    }

    public function remove($id)
    {
        $products = $this->all();
        unset($products[$id]);
        App::getSession()->set('cart', $products);

        return $this;
    }

    public function increment($id)
    {
        if(empty($product = $this->getById($id))) {
            return false;
        }

        $product->count++;

        return $this->save($product);
    }

    public function decrement($id)
    {
        if(empty($product = $this->getById($id))) {
            return false;
        }

        $product->count > 1 && $product->count--;

        return $this->save($product);
    }

    public function sum()
    {
        $sum = 0;

        foreach ($this->all() as $product) {
            $sum += $product->price * $product->count;
        }

        return $sum;
    }

}