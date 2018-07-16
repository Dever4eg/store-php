<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:48
 */

namespace App\Controllers;


use App\Models\Product;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface;
use Src\App;
use Src\Authorization\Auth;
use Src\Controller;
use Src\View;
use Zend\Diactoros\Response\HtmlResponse;

class ProductsController extends Controller
{
    public function index(ServerRequestInterface $request)
    {
        $products = Product::all([
            'limit' => 12,
            'offset' => $request->getQueryParams()['page'] ?? 1,
        ]);

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