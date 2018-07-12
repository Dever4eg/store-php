<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:48
 */

namespace App\Controllers;


use Src\Controller;
use Src\View;

class ProductsController extends Controller
{
    public function index()
    {
        $products = require APP_PATH . '/products_data.php';
        (new View("main") )
            ->withParam("products", $products)
            ->display();
    }

    public function show()
    {
        $products = require APP_PATH . '/products_data.php';
        $product = $products[$_GET['id']];

        (new View("product"))
            ->withParam("product", $product)
            ->display();
    }
}