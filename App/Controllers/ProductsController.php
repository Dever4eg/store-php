<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:48
 */

namespace App\Controllers;


use App\Models\Product;
use Src\App;
use Src\Controller;
use Src\View;

class ProductsController extends Controller
{
    public function index()
    {

        $product = Product::getById(7);
        $product->title = $product->title . "-e-";
        $product->save();

        var_dump($product);


//        $products = require APP_PATH . '/Models/products_data.php';
//        return (new View("main") )
//            ->withParam("products", $products)
//            ->getHtmlResponse();
    }

    public function show()
    {
        $products = require APP_PATH . '/Models/products_data.php';
        $product = $products[$_GET['id']];

        return (new View("product"))
            ->withParam("product", $product)
            ->getHtmlResponse();
    }
}