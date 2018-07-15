<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:48
 */

namespace App\Controllers;


use App\Models\Product;
use Psr\Http\Message\ServerRequestInterface;
use Src\App;
use Src\Controller;
use Src\View;
use Zend\Diactoros\Response\HtmlResponse;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return (new View("main") )
            ->withParam("products", $products)
            ->getHtmlResponse();
    }

    public function show(ServerRequestInterface $request)
    {
        $product = Product::getById($request->getQueryParams()['id']);

        return (new View("product"))
            ->withParam("product", $product)
            ->getHtmlResponse();
    }
}