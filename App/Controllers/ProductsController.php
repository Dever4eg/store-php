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
use Src\Exceptions\Http\Error404Exception;
use Src\View;
use Zend\Diactoros\Response\HtmlResponse;

class ProductsController extends Controller
{
    public function index(ServerRequestInterface $request)
    {
        $products = Product::query()->paginate(12, $request);


        return (new View("main") )
            ->withParam("products", $products['results'])
            ->withParam("pagination", $products)
            ->getHtmlResponse();
    }

    public function show(ServerRequestInterface $request)
    {
        $r = $request->getQueryParams();
        $v =$this->validate($r, [
            'id'    => "required",
        ]);
        if($v->fails()) throw new Error404Exception();

        $product = Product::getById($r['id']);

        if(empty($product)) throw new Error404Exception();


        return (new View("product"))
            ->withParam("product", $product)
            ->getHtmlResponse();
    }

    public function create()
    {
        $view = new View('admin/products/new');
        return $view->getHtmlResponse();
    }

    public function store(ServerRequestInterface $request)
    {
        var_dump($request->getParsedBody());die;
    }

}